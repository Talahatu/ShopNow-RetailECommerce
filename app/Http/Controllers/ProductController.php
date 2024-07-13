<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductStockHistory;
use App\Models\Shop;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDO;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $stockHistories = ProductStockHistory::with(["product.images", "product.shop" => function ($query) use ($shop) {
            $query->where("id", $shop->id);
        }])->whereHas("product.shop", function ($query) use ($shop) {
            $query->where("id", $shop->id);
        })->orderBy("date", "DESC")
            ->get();
        return view('merchant.product', compact("shop", "stockHistories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view('merchant.product-create', compact("shop"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required',
            'image.*' => "mimes:jpg,png,jpeg|max:4000",
            'desc' => 'required',
            'category' => 'required',
            'weight' => 'required|numeric',
            'price' => 'required',
            'stock' => 'required|numeric'
        ]);
        $price = str_replace(".", "", $request->get("price"));
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        Product::insertNewProduct($request, $shop->id, $price);
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Product::find($id);
        $category = Category::find($data->category_id);
        $brand = Brand::find($data->brand_id);
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $images = Image::where("product_id", $id)->get();
        return view('merchant.product-update', compact("data", "category", "brand", "shop", "images"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'desc' => 'required',
            'category' => 'required',
            'weight' => 'required|numeric',
            'price' => 'required',
            'stock' => 'required|numeric',
            'image.*' => "mimes:jpg,png,jpeg|max:4000",
        ]);
        $price = str_replace(".", "", $request->get("price"));
        Product::updateProduct($request, $id, $price);
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            // Product::deleteImages($id);
            Product::find($id)->delete();
        });
        return response()->json(true);
    }

    public function fetchLive()
    {
        $data = Product::getProducts(Auth::user()->id, "live");
        return response()->json(["data" => $data]);
    }
    public function fetchRepopulate(Request $request)
    {
        $type = $request->get("type");
        $products = Product::getProducts(Auth::user()->id, $type);
        return response()->json(["data" => $products]);
    }

    public function archiveProduct(Request $request)
    {
        $prod = Product::find($request->get("id"));
        $prod->status = "archive";
        $prod->save();
        return response()->json(["status" => "OK", "data" => $prod]);
    }
    public function liveProduct(Request $request)
    {
        $prod = Product::find($request->get("id"));
        $prod->status = "live";
        $prod->save();
        return response()->json(["status" => "OK", "data" => $prod]);
    }
    public function loadProduct(Request $request)
    {
        $lat = $request->get("lat");
        $long = $request->get("long");
        if (Auth::check()) {
            $addr = Address::where([
                ["user_id", Auth::user()->id],
                ["current", true]
            ])->first();
            $lat = $addr->lat;
            $long = $addr->long;
        }
        $products = Product::getClosestProduct($lat, $long);
        return response()->json(compact("products"));
    }
    public function searchProduct(Request $request)
    {
        $lat = $request->get("lat");
        $long = $request->get("long");
        $query = $request->get("query");
        $filter = null;
        if ($request->get("filter")) {
            $filter = $request->get("filter");
        }
        if (Auth::check()) {
            $addr = Address::where([
                ["user_id", Auth::user()->id],
                ["current", true]
            ])->first();
            $lat = $addr->lat;
            $long = $addr->long;
        }
        $products = Product::getClosestProduct($lat, $long, $query == "all" ? "" : $query, $filter);
        return response()->json(compact("products"));
    }
    public function showProduct($id)
    {
        $prod = Product::find($id);
        $prod->load("category", "brand", "images", "shop");
        $relatedProd = Product::where("id", "!=", $id)
            ->where(function ($query) use ($prod) {
                $query->where("category_id", $prod->category_id)->orWhere("brand_id", $prod->brand_id);
            })->get();
        $wishlistStat = Product::join("wishlist", "wishlist.product_id", "products.id")->where("wishlist.product_id", $id)->first();

        $review = ProductReview::with(["user", "product"])->where("product_id", $id)->get();

        if (session()->has("rvp")) {
            if (!in_array($id, session('rvp'))) {
                if (count(session('rvp')) == 4) {
                    $rvp = session('rvp');
                    array_shift($rvp);
                    session(["rvp" => $rvp]);
                }
                session()->push("rvp", $id);
            }
        } else {
            session()->push("rvp", $id);
        }

        return view("regular.product-info", ["data" => $prod, "related" => $relatedProd, "wishlist" => $wishlistStat, "reviews" => $review]);
    }
    public function buyNowCart(Request $request)
    {
        Product::uncheckAllCartItem();
        $idCart = Product::insertCart($request);
        $cart = Cart::find($idCart);
        $cart->selected = 1;
        $cart->save();
        return response()->json($idCart);
    }
    public function addToCart(Request $request)
    {
        $result = Product::insertCart($request);
        $product = Product::find($request->get("id"));
        return response()->json(["status" => $result, "data" => $product]);
    }
    public function removeFromCart(Request $request)
    {
        $status = Cart::find($request->get("id"))->delete();
        return response()->json($status);
    }
    public function showCart()
    {
        $data = Cart::join("products AS p", "p.id", "cart.product_id")
            ->join("images AS i", "i.product_id", "p.id")
            ->where("cart.user_id", Auth::user()->id)
            ->get(["p.name AS pname", "cart.price", "cart.qty", "cart.selected", "cart.distance", "p.weight", "i.name AS iname", "cart.id", "p.id AS pid", "p.stock"]);

        $cartTotal = 0;
        $shippingFee = 0;
        $total = 0;
        if (count($data) > 0) {
            $cartTotal = Product::cartTotal(Auth::user()->id);
            $shippingFee = Product::shippingFee(Auth::user()->id);
            $total = $cartTotal + $shippingFee;
        }
        return view('regular.cart', compact("data", "cartTotal", "shippingFee", "total"));
    }
    public function updateCartQuantity(Request $request)
    {
        $cart = Cart::find($request->get("id"));
        $cart->qty = $request->get("qty");
        $cart->save();
        return response()->json($cart);
    }
    public function updateCartSelected(Request $request)
    {
        $cart = Cart::find($request->get("id"));
        $cart->selected = $request->get("value") == "true" ? true : false;
        $cart->save();
        return response()->json(["data" => $cart]);
    }

    public function showCheckout()
    {
        $address = Address::where([
            ["user_id", Auth::user()->id],
            ["current", 1]
        ])->first();
        $carts = Product::select("products.id", "products.name AS pname", "cart.price", "products.weight", "cart.qty", "cart.distance", "images.name AS iname")
            ->join("cart", "cart.product_id", "products.id")
            ->leftJoin("images", "images.product_id", "products.id")
            ->where([
                ["user_id", Auth::user()->id],
                ["selected", 1]
            ])->get();
        $cartTotal = Product::cartTotal(Auth::user()->id);
        $shippingFee = Product::shippingFee(Auth::user()->id);
        $total = $cartTotal + $shippingFee;
        return view("regular.checkout", compact("carts", "address", "cartTotal", "shippingFee", "total"));
    }

    public function getShipModal(Request $request)
    {
        $addresses = Address::where("user_id", Auth::user()->id)->get();
        $id = $request->get("id");
        return response()->json([
            "content" => view("regular.modals.changeShipAddress", compact("addresses", "id"))->render()
        ]);
    }

    public function changeShipAddress(Request $request)
    {
        $addressID = $request->get("addrID");
        Product::changeDistanceInCart($addressID);
        $cartTotal = Product::cartTotal(Auth::user()->id);
        $shippingFee = Product::shippingFee(Auth::user()->id);
        $total = $cartTotal + $shippingFee;
        $saldo = Auth::user()->saldo;
        return response()->json(compact("cartTotal", "shippingFee", "total", "saldo"));
    }

    public function showWishlist()
    {
        $products = Product::select(DB::raw("MIN(images.name) AS iname"), "products.name AS pname", "categories.name AS cname", "products.price", "products.stock", "products.rating", "products.id")
            ->join("wishlist", 'wishlist.product_id', "products.id")
            ->join("categories", "categories.id", "products.category_id")
            ->leftJoin("images", "images.product_id", "products.id")
            ->where("wishlist.user_id", Auth::user()->id)
            ->groupBy(['products.id', 'pname', "products.stock", "products.rating", "products.price", 'cname'])
            ->get();
        return view('regular.wishlist', compact("products"));
    }
    public function toggleWishlist(Request $request)
    {
        $prod = Product::find($request->get("id"));
        if ($request->get("state") == "true") {
            $new = new Wishlist();
            $new->product_id = $request->get("id");
            $new->user_id = Auth::user()->id;
            $new->save();
            return response()->json($prod);
        }
        Wishlist::where([
            ["user_id", Auth::user()->id],
            ["product_id", $request->get("id")]
        ])->delete();
        return response()->json($prod);
    }
}
