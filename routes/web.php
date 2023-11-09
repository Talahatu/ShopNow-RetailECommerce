<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
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
Route::get('/reregister', [HomeController::class, 'reregister'])->name('reregister');
Route::middleware(["auth"])->group(function () {
    Route::get("/change-email-show", [HomeController::class, 'changeEmailShow'])->name('change.email.show');
    Route::post("/change-email", [HomeController::class, 'changeEmail'])->name('change.email');
    Route::get("/verify-email", [HomeController::class, "verifyLoggedEmail"])->name("verify.logged.email");

    Route::get("/profile", [UserController::class, "profile"])->name('profile');
    Route::put("/profile/update", [UserController::class, 'updateProfile'])->name("profile.update");

    Route::post("/getAddAddressForm", [UserController::class, "getAddAddressForm"])->name("address.form");
    Route::post("/add-new-address", [UserController::class, "addNewAddress"])->name("address.create");
});

Route::middleware(["auth", "seller"])->group(function () {
    Route::resource("seller", ShopController::class);
});


Route::get("/test", function () {
    return view('regular.modals.newAddressForm');
});
