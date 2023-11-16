<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        return view('merchant.product', compact("shop"));
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
            Product::deleteImages($id);
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
}
