<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
Route::get("/categories", [HomeController::class, "categoriesShow"])->name("show.categories");
Route::get("/show-product/{id}", [ProductController::class, "showProduct"])->name("show.product");
Route::get('/reregister', [HomeController::class, 'reregister'])->name('reregister');
Route::post('/loadProduct', [ProductController::class, "loadProduct"])->name('loadProduct');
Route::post('/searchProduct', [ProductController::class, "searchProduct"])->name('searchProduct');
Route::middleware(["auth"])->group(function () {
    Route::get("/change-email-show", [HomeController::class, 'changeEmailShow'])->name('change.email.show');
    Route::post("/change-email", [HomeController::class, 'changeEmail'])->name('change.email');
    Route::get("/verify-email", [HomeController::class, "verifyLoggedEmail"])->name("verify.logged.email");

    Route::get("/profile", [UserController::class, "profile"])->name('profile');
    Route::put("/profile/update", [UserController::class, 'updateProfile'])->name("profile.update");
    Route::post("/getAddAddressForm", [UserController::class, "getAddAddressForm"])->name("address.form");
    Route::post("/getUpdateAddAddressForm", [UserController::class, "getUpdateAddAddressForm"])->name("address.form.update");
    Route::post("/add-new-address", [UserController::class, "addNewAddress"])->name("address.create");
    Route::delete("/delete-address", [UserController::class, "deleteAddress"])->name("address.destroy");
    Route::post("/set-cur-addr", [UserController::class, "setCurAddr"])->name("address.set");

    Route::get('/cart', [ProductController::class, "showCart"])->name("cart.show");
    Route::post("/cart/add", [ProductController::class, "addToCart"])->name("cart.add");
    Route::delete("/cart/delete", [ProductController::class, "removeFromCart"])->name("cart.remove");
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
    return view('regular.product-info');
});
