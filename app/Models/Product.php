<?php

namespace App\Models;

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


    public static function getProducts($userID, $type)
    {
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
            $prod->category_id = $request->get("category");
            $prod->brand_id = $request->get("brand");
            $prod->name = $request->get("name");
            $prod->description = $request->get("desc");
            $prod->weight = $request->get("weight");
            $prod->stock = $request->get("stock");
            $prod->price = $price;
            $prod->save();
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

    public static function getClosestProduct($latitude, $longitude, $query = '')
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
            ->where("products.name", "LIKE", $modQuery)
            ->orWhere("categories.name", "LIKE", $modQuery)
            ->orWhere("brands.name", "LIKE", $modQuery)
            ->groupBy(['products.id', 'pname', "products.stock", "products.rating", "products.weight", "products.price", 'cname', 'bname'])
            ->orderBy('distance')
            ->get();
    }

    public static function insertCart($request)
    {
        DB::transaction(function () use ($request) {
            $address = User::join("addresses", "addresses.user_id", "users.id")
                ->where([
                    ["addresses.current", true],
                    ["addresses.user_id", Auth::user()->id]
                ])->first(["lat", "long"]);
            $distance = Product::getDistance($request->get("id"), $address->lat, $address->long);
            $newCart = new Cart();
            $newCart->user_id = Auth::user()->id;
            $newCart->product_id = $request->get("id");
            $newCart->qty = $request->get("qty");
            $newCart->price = $request->get("price");
            $newCart->distance = $distance;
            $newCart->save();
        });
        return true;
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
}
