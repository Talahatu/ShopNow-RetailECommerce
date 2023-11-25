<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);
Route::get('/', function () {
    return redirect("/home");
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get("/search/{query}", [HomeController::class, "searchShow"])->name("show.search");
Route::get("/categories", [CategoryController::class, "categoriesShow"])->name("show.categories");
Route::get("/show-product/{id}", [ProductController::class, "showProduct"])->name("show.product");
Route::get('/reregister', [HomeController::class, 'reregister'])->name('reregister');
Route::post('/loadProduct', [ProductController::class, "loadProduct"])->name('loadProduct');
Route::post('/searchProduct', [ProductController::class, "searchProduct"])->name('searchProduct');
Route::middleware(["auth"])->group(function () {
    Route::get("/change-email-show", [HomeController::class, 'changeEmailShow'])->name('change.email.show');
    Route::post("/change-email", [HomeController::class, 'changeEmail'])->name('change.email');
    Route::get("/verify-email", [HomeController::class, "verifyLoggedEmail"])->name("verify.logged.email");

    Route::get("/profile", [UserController::class, "profile"])->name('profile');
    Route::get("/profile/notif", [UserController::class, "profileNotif"])->name("profile.notif");
    Route::get("/profile/order", [UserController::class, "profileOrder"])->name("profile.order");
    Route::get("/profile/bio", [UserController::class, "profileBio"])->name("profile.bio");

    Route::put("/profile/update", [UserController::class, 'updateProfile'])->name("profile.update");
    Route::post("/getAddAddressForm", [UserController::class, "getAddAddressForm"])->name("address.form");
    Route::post("/getUpdateAddAddressForm", [UserController::class, "getUpdateAddAddressForm"])->name("address.form.update");
    Route::post("/add-new-address", [UserController::class, "addNewAddress"])->name("address.create");
    Route::delete("/delete-address", [UserController::class, "deleteAddress"])->name("address.destroy");
    Route::post("/set-cur-addr", [UserController::class, "setCurAddr"])->name("address.set");
    Route::post("/saldo/topup", [UserController::class, "topup"])->name("saldo.topup");

    Route::get('/cart', [ProductController::class, "showCart"])->name("cart.show");
    Route::post('/cart/buynow', [ProductController::class, "buyNowCart"])->name("cart.buynow");
    Route::post("/cart/add", [ProductController::class, "addToCart"])->name("cart.add");
    Route::delete("/cart/delete", [ProductController::class, "removeFromCart"])->name("cart.remove");
    Route::post("/cart/updateQuantity", [ProductController::class, "updateCartQuantity"])->name("cart.updateQTY");
    Route::post("/cart/updateSelected", [ProductController::class, "updateCartSelected"])->name("cart.updateSelected");

    Route::get("/checkout", [ProductController::class, "showCheckout"])->name("checkout.show");
    Route::post("/checkout/create", [OrderController::class, "checkoutCreate"])->name("checkout.create");
    Route::post("/getShipAddressModal", [ProductController::class, "getShipModal"])->name("checkout.ship.modal");

    Route::get("/wishlist", [ProductController::class, "showWishlist"])->name("wishlist.show");
    Route::post("/wishlist/toggle", [ProductController::class, "toggleWishlist"])->name("wishlist.toggle");
});

Route::middleware(["auth", "seller"])->group(function () {
    Route::resource("seller", ShopController::class);
    Route::resource('product', ProductController::class);
    Route::post("/fetch-categories", [CategoryController::class, "getCategories"]);
    Route::post("/fetch-brands", [BrandController::class, "getBrands"]);
    Route::post('/fetch/product/live', [ProductController::class, "fetchLive"]);
    Route::post('/fetch/product/repopulate', [ProductController::class, "fetchRepopulate"]);
    Route::put("/archive/product", [ProductController::class, "archiveProduct"]);
    Route::put("/live/product", [ProductController::class, "liveProduct"]);
});


Route::get("/test", function () {
    return view('layouts.index2');
});

Route::get("/test2", function () {
    $cart = Shop::with(['products.carts' => function ($query) {
        $query->where("user_id", Auth::user()->id)->where("selected", "1");
    }])
        ->where("shops.id", 1)
        ->get();

    $carts = Cart::join("products", "products.id", "cart.product_id")
        ->join("shops", "shops.id", "products.shop_id")
        ->where([
            ["cart.user_id", Auth::user()->id],
            ["cart.selected", "1"]
        ])
        ->orderBy("products.shop_id")
        ->get(["products.shop_id"]);
    $allShopID = $carts->unique('shop_id')->pluck("shop_id")->values();


    $shopProductCart = Cart::join("products", "products.id", "cart.product_id")
        ->join("shops", "shops.id", "products.shop_id")
        ->where("products.shop_id", 2)
        ->where([
            ["cart.user_id", Auth::user()->id],
            ["cart.selected", "1"]
        ])
        ->get();
    dd($shopProductCart);

    dd($cart[0]->products[1]->carts);
    return true;
});
