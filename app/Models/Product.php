<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = ["name", "description", 'weight', "stock", "price", "status", "rating", "shop_id", "category_id"];

    public function images()
    {
        return $this->hasMany(Image::class, "product_id", "id");
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, "brand_id", "id");
    }
    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }
    public function carts()
    {
        return $this->hasMany(Cart::class, "product_id", "id");
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, "product_id", "id");
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, "product_id", "id");
    }


    public static function getProducts($userID, $type)
    {
        $type = ($type == "empty" ? "out of stock" : $type);
        return Product::join("shops", "shops.id", "products.shop_id")
            ->where([
                ["shops.user_id", $userID],
                ["products.status", $type]
            ])->get(["products.id", "products.name", "products.SKU", 'products.price', 'products.stock']);
    }
    public static function insertNewProduct($request, $shopID, $price)
    {
        DB::transaction(function () use ($request, $shopID, $price) {
            $category = Category::find($request->get("category"));
            $brand = Brand::find($request->get("brand"));
            $newProd = new Product();
            $newProd->shop_id = $shopID;
            $newProd->category_id = $request->get("category");
            $newProd->brand_id = $request->get("brand");
            $newProd->name = $request->get("name");
            $newProd->description = $request->get("desc");
            $newProd->weight = $request->get("weight");
            $newProd->stock = $request->get("stock");
            $newProd->price = $price;
            $newProd->status = "live";
            $newProd->SKU = Product::getSKU($category->name, $brand->name, $request->get("name"));
            $newProd->save();

            $prodHistory = new ProductStockHistory();
            $prodHistory->product_id = $newProd->id;
            $prodHistory->addition = $request->get("stock");
            $prodHistory->substraction = 0;
            $prodHistory->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateString();
            $prodHistory->save();

            if ($request->hasFile("image")) {
                foreach ($request->file("image") as $key => $value) {
                    $file = $value;
                    $ext = $file->getClientOriginalExtension();
                    $filename = "product_" . hrtime(true) . "." . $ext;
                    $path = public_path() . '/productimages';
                    $file->move($path, $filename);

                    $newImage = new Image();
                    $newImage->name = $filename;
                    $newImage->product_id = $newProd->id;
                    $newImage->save();
                }
            }
        });
        return true;
    }

    public static function updateProduct($request, $prodID, $price)
    {
        DB::transaction(function () use ($request, $prodID, $price) {
            $prod = Product::find($prodID);

            $prodHistory = new ProductStockHistory();
            $temp = $prod->stock - $request->get("stock");
            $prodHistory->addition = ($temp < 0 ? 0 : $temp);
            $prodHistory->substraction = ($temp < 0 ? $temp : 0);

            $prod->category_id = $request->get("category");
            $prod->brand_id = $request->get("brand");
            $prod->name = $request->get("name");
            $prod->description = $request->get("desc");
            $prod->weight = $request->get("weight");
            $prod->stock = $request->get("stock");
            $prod->price = $price;
            $prod->status = ($prod->stock <= 0 ? "out of stock" : $prod->status);
            $prod->save();

            $prodHistory->product_id = $prod->id;
            $prodHistory->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateString();
            $prodHistory->save();

            if ($request->hasFile("image")) {
                Product::deleteImages($prodID);
                foreach ($request->file("image") as $key => $value) {
                    $file = $value;
                    $ext = $file->getClientOriginalExtension();
                    $filename = "product_" . hrtime(true) . "." . $ext;
                    $path = public_path() . '/productimages';
                    $file->move($path, $filename);

                    $newImage = new Image();
                    $newImage->name = $filename;
                    $newImage->product_id = $prodID;
                    $newImage->save();
                }
            }
        });
        return true;
    }

    private function getSKU($category, $brand, $name)
    {
        return substr($category, 0, 1) . substr($brand, 0, 1) . substr($name, 0, 1) . now()->format("Ymds");
    }

    public static function deleteImages($id)
    {
        $status = false;
        $images = Image::where("product_id", $id)->get(["name", "id"]);
        foreach ($images as $key => $value) {
            $path = public_path('productimages/' . $value->name);
            if (file_exists($path)) {
                $status = Image::find($value->id)->delete();
                unlink($path);
            }
        }
        return $status;
    }

    public static function getClosestProduct($latitude, $longitude, $query = '', $filter = null)
    {
        $modQuery = "%$query%";
        return Product::select(
            'products.id',
            'products.name as pname',
            "products.stock",
            "products.rating",
            "products.weight",
            "products.price",
            'categories.name as cname',
            'brands.name as bname',
            DB::raw('MIN(images.name) as iname'),
            DB::raw('SQRT(
                POW((RADIANS(MIN(shops.long)) - RADIANS(' . $longitude . ')) * COS((RADIANS(' . $latitude . ') 
                + RADIANS(MIN(shops.lat))) / 2), 2) +
                POW((RADIANS(MIN(shops.lat)) - RADIANS(' . $latitude . ')), 2)
                ) * 6371 AS distance')
        )
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('shops', 'shops.id', '=', 'products.shop_id')
            ->leftJoin('images', 'images.product_id', '=', 'products.id')
            ->when($filter != null, function ($q) use ($filter) {
                return $q->where(function ($q2) use ($filter) {
                    return $q2->when($filter["categories"] != "empty", function ($query) use ($filter) {
                        return $query->whereIn("products.category_id", $filter["categories"]);
                    })->when($filter["brands"] != "empty", function ($query) use ($filter) {
                        return $query->orWhereIn("products.brand_id", $filter["brands"]);
                    });
                })->when($filter["priceFrom"] > 0 && $filter["priceTo"] > 0, function ($query) use ($filter) {
                    return $query->whereBetween("products.price", [$filter["priceFrom"], $filter["priceTo"]]);
                })->when($filter["priceFrom"] > 0, function ($query) use ($filter) {
                    return $query->where("products.price", ">=", $filter["priceFrom"]);
                })->when($filter["priceTo"] > 0, function ($query) use ($filter) {
                    return $query->where("products.price", "<=", $filter["priceTo"]);
                });
            })
            ->where(function ($query) use ($modQuery) {
                return $query
                    ->where("products.name", "LIKE", $modQuery)
                    ->orWhere("categories.name", "LIKE", $modQuery)
                    ->orWhere("brands.name", "LIKE", $modQuery);
            })
            ->groupBy(['products.id', 'pname', "products.stock", "products.rating", "products.weight", "products.price", 'cname', 'bname'])
            ->orderBy('distance')
            ->get();
    }

    public static function uncheckAllCartItem()
    {
        return Cart::where("user_id", Auth::user()->id)->update(["selected" => 0]);
    }

    public static function insertCart($request)
    {
        $cartID = null;
        DB::transaction(function () use ($request, &$cartID) {
            $address = User::join("addresses", "addresses.user_id", "users.id")
                ->where([
                    ["addresses.current", true],
                    ["addresses.user_id", Auth::user()->id]
                ])->first(["lat", "long", "addresses.id"]);
            $distance = Product::getDistance($request->get("id"), $address->lat, $address->long);

            $existsCart = Cart::where([
                ["product_id", $request->get("id")],
                ["user_id", Auth::user()->id]
            ])->first();

            if ($existsCart) {
                $existsCart->qty = $existsCart->qty + $request->get("qty");
                $existsCart->save();
                $cartID = $existsCart->id;
            } else {
                $newCart = new Cart();
                $newCart->user_id = Auth::user()->id;
                $newCart->product_id = $request->get("id");
                $newCart->qty = $request->get("qty");
                $newCart->price = $request->get("price");
                $newCart->distance = $distance;
                $newCart->save();
                $cartID = $newCart->id;
            }
        });
        return $cartID;
    }

    public static function getDistance($id, $latitude, $longitude)
    {
        $product = Product::join('shops', 'shops.id', '=', 'products.shop_id')->select(DB::raw('SQRT(
            POW((RADIANS(MIN(shops.long)) - RADIANS(' . $longitude . ')) * COS((RADIANS(' . $latitude . ') 
            + RADIANS(MIN(shops.lat))) / 2), 2) +
            POW((RADIANS(MIN(shops.lat)) - RADIANS(' . $latitude . ')), 2)
            ) * 6371 AS distance'))->where("products.id", $id)->first();
        return $product->distance;
    }

    public static function cartTotal($userID)
    {
        $result = Product::select(DB::raw("SUM(cart.qty * cart.price) AS cartTotal"))
            ->join("cart", "cart.product_id", "products.id")
            ->where([
                ["cart.user_id", $userID],
                ["cart.selected", 1]
            ])->first();
        return $result->cartTotal;
    }
    public static function shippingFee($userID)
    {
        $result = Product::select(DB::raw("SUM(CalculateFeeByWeight(products.weight*cart.qty) + CalculateFeeByDistance(cart.distance)) AS shippingFee"))
            ->join("cart", "cart.product_id", "products.id")
            ->where([
                ["cart.user_id", $userID],
                ["cart.selected", 1]
            ])->first();
        return $result->shippingFee;
    }

    public static function changeDistanceInCart($addressID)
    {
        DB::transaction(function () use ($addressID) {
            $selectedProducts = Cart::where([["user_id", Auth::user()->id], ["selected", 1]])->get(["id", "product_id"]);
            $selectedAddress = Address::where("id", $addressID)->first(["lat", "long"]);
            foreach ($selectedProducts as $value) {
                $distance = Product::getDistance($value->product_id, $selectedAddress->lat, $selectedAddress->long);
                $updatedProduct = Cart::find($value->id);
                $updatedProduct->distance = $distance;
                $updatedProduct->save();
            }
            return true;
        });
    }
}
