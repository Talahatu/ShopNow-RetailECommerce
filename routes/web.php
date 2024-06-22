<?php

use App\Events\Chat;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

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
Route::post("/getOrderPaymentType", [OrderController::class, "getPaymentType"])->name("order.payment.type");
Route::middleware("either")->group(function () {
    Route::post("/pushSubscription", [UserController::class, "userPushSubscribe"]);
});
Route::middleware(["prevent.courier"])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get("/search/{query}", [HomeController::class, "searchShow"])->name("show.search");
    Route::get("/categories", [CategoryController::class, "categoriesShow"])->name("show.categories");
    Route::get("/show-product/{id}", [ProductController::class, "showProduct"])->name("show.product");
    Route::get('/reregister', [HomeController::class, 'reregister'])->name('reregister');
    Route::post('/loadProduct', [ProductController::class, "loadProduct"])->name('loadProduct');
    Route::post('/searchProduct', [ProductController::class, "searchProduct"])->name('searchProduct');
    Route::get("/shop/{id}", [HomeController::class, "showShop"])->name("shop.show");

    Route::get('/courier/login', [CourierController::class, "showLoginPage"])->name("courier.show.login");
    Route::post('/courier/login/process', [CourierController::class, "loginAttempt"])->name("courier.login.attempt");
});
Route::middleware(["auth", "prevent.courier"])->group(function () {
    Route::get("/change-email-show", [HomeController::class, 'changeEmailShow'])->name('change.email.show');
    Route::post("/change-email", [HomeController::class, 'changeEmail'])->name('change.email');
    Route::get("/verify-email", [HomeController::class, "verifyLoggedEmail"])->name("verify.logged.email");

    Route::get("/profile", [UserController::class, "profile"])->name('profile');
    Route::get("/profile/notif", [UserController::class, "profileNotif"])->name("profile.notif");
    Route::get("/profile/order", [UserController::class, "profileOrder"])->name("profile.order");
    Route::get("/profile/bio", [UserController::class, "profileBio"])->name("profile.bio");

    Route::post("/profile/order/detail", [UserController::class, "getOrderDetail"])->name("profile.order.detail");
    Route::post("/profile/order/finish", [UserController::class, "orderFinish"])->name("profile.order.finish");
    Route::post("/profile/order/giveRating", [UserController::class, "giveRating"]);
    Route::post("/profile/order/cancel", [UserController::class, "cancelOrder"]);
    Route::post("/profile/order/changeTab", [UserController::class, "changeTab"]);

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
    Route::post('/changeCartAddress', [ProductController::class, 'changeShipAddress'])->name("checkout.addr.chng");

    Route::get("/wishlist", [ProductController::class, "showWishlist"])->name("wishlist.show");
    Route::post("/wishlist/toggle", [ProductController::class, "toggleWishlist"])->name("wishlist.toggle");

    Route::get("/chat/{id}", [ChatController::class, "showChat"])->name("chat.show");
    Route::get("/allChat", [ChatController::class, "showAllChat"])->name("allChat.show");
    Route::post("/chat/loadChats", [ChatController::class, "loadChats"])->name("chat.load");
    Route::post("/sendMessage", [ChatController::class, "sendMessage"])->name("chat.send");

    Route::get("/openSellerAcc", [UserController::class, "openAccount"])->name('seller.hub');
    Route::post("/processAcc", [UserController::class, "processSellerAcc"])->name('seller.acc.process');

    Route::post("/getAllRelatedShop", [UserController::class, "getAllRelatedShop"])->name("get.all");
    Route::post("/getLoggedIn", [UserController::class, "getLoggedInID"]);

    Route::post("/notification/change/denied", [UserController::class, "notificationDenied"]);
    // not used yet
    Route::post("/getSeller", [ShopController::class, "getSeller"])->name("seller.get");
});

Route::middleware(["auth", "seller", "prevent.courier"])->group(function () {
    Route::resource("seller", ShopController::class);
    Route::resource('product', ProductController::class);
    Route::get('/myorder', [OrderController::class, "ordersIndex"])->name("order.index");
    Route::post("/fetch/order/repopulate", [OrderController::class, "fetchRepopulate"]);
    Route::post("/fetch/order/new", [OrderController::class, "fetchNewOrder"])->name("order.new");
    Route::post("/order/accept", [OrderController::class, "acceptOrder"])->name("order.accept");
    Route::post("/order/reject", [OrderController::class, "rejectOrder"])->name("order.reject");
    Route::post("/order/detail", [OrderController::class, "detailOrder"])->name("order.detail");
    Route::post("/getIdsFromOrder", [OrderController::class, "getIDs"])->name("order.getID");
    Route::post("/delivery/detail", [OrderController::class, "deliveryDetail"])->name("order.delivery.detail");
    Route::post("/delivery/done", [OrderController::class, "deliveryDone"]);

    Route::get("/myCourier", [CourierController::class, "courierIndex"])->name("courier.index");
    Route::get("/createCourier", [CourierController::class, "create"])->name("courier.create");
    Route::post("/storeCourier", [CourierController::class, "store"])->name("courier.store");
    Route::get("/courier/update/{id}", [CourierController::class, "showUpdatePage"])->name("courier.show.update");
    Route::post("/updateCourier", [CourierController::class, "update"])->name("courier.update");
    Route::post("/getAllCourier", [CourierController::class, "getAllByShop"])->name("courier.all");
    Route::post("/fetch-courier", [CourierController::class, "fetchCourier"])->name("courier.fetch");
    Route::post("/pickCourier", [OrderController::class, "pickCourier"])->name("order.courier");
    Route::delete('/courier/delete/{id}', [CourierController::class, "deleteCourier"])->name("courier.delete");

    Route::post("/fetch-categories", [CategoryController::class, "getCategories"]);
    Route::post("/fetch-brands", [BrandController::class, "getBrands"]);
    Route::post('/fetch/product/live', [ProductController::class, "fetchLive"]);
    Route::post('/fetch/product/repopulate', [ProductController::class, "fetchRepopulate"]);
    Route::put("/archive/product", [ProductController::class, "archiveProduct"]);
    Route::put("/live/product", [ProductController::class, "liveProduct"]);

    Route::get("/seller-financial", [ShopController::class, "showFinancials"])->name("seller.financial");
    Route::post("/seller/financial/sold-chart", [ShopController::class, "productSold"])->name("seller.sold.chart");
    Route::post("/seller/financial/popular", [ShopController::class, "top5Popular"])->name("seller.top5Popular");

    Route::get("/seller/chat/show", [ShopController::class, "showChat"])->name("seller.chat");
    Route::post("/sendMessageSeller", [ChatController::class, "sendMessageSeller"])->name("chat.send.seller");
});

Route::middleware(['auth.courier'])->group(function () {
    Route::get("/courier/home", [CourierController::class, "courierHome"])->name('courier.home');
    Route::post("/courier/logout", [CourierController::class, "logout"])->name("courier.logout");
    Route::post("/getDeliveryDetail", [CourierController::class, "getDetail"])->name("courier.detail");
    Route::post("/pickupItems", [CourierController::class, "pickupItems"])->name("courier.pickup");
    Route::get("/courier/delivery/{id}/{deliveryId}", [CourierController::class, "deliveryDetail"])->name("courierDelivery");
    Route::post("/delivery/finish", [CourierController::class, "deliveryFinish"])->name("courier.finish");
    Route::post("/getposition/deliveries", [CourierController::class, "getDeliveriesPosition"]);

    Route::get("/courier/history", [CourierController::class, "courierHistory"])->name("courier.history");
    Route::get("/courier/fee/history", [CourierController::class, "courierFeeHistory"])->name("courier.fee.history");

    Route::post("/courier/fee/withdraw", [CourierController::class, "courierWithdraw"])->name("courier.fee.withdraw");
    Route::post("/nearDestination", [CourierController::class, "nearDestination"]);
});

Route::get("/testing", [HomeController::class, "testing"])->middleware("either");
