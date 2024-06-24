<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Courier;
use App\Models\FinancialHistory;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductStockHistory;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\OrderNotification;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pusher\PushNotifications\PushNotifications;

use function Ramsey\Uuid\v1;

class UserController extends Controller
{
    //
    public function profile()
    {
        return redirect()->route('profile.bio');
    }

    public function profileNotif()
    {
        $notifs = Notification::where("user_id", Auth::user()->id)->get();
        return view('regular.tabs.notif-tab', compact("notifs"));
    }
    public function profileBio()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view('regular.tabs.profile-tab', compact("shop"));
    }
    public function profileOrder()
    {
        $pendings = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "new"]
            ])
            ->orderBy("shop_id")
            ->get();
        $processed = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "accepted"]
            ])
            ->orderBy("shop_id")
            ->get();
        $sents = Order::with(["shop", "details.product.images", "deliveries"])
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "sent"]
            ])
            ->orderBy("shop_id")
            ->get();
        $finished = Order::with(["shop", "details.product.images", "deliveries"])
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "done"]
            ])
            ->orderBy("shop_id")
            ->get();
        $cancelled = Order::with(["shop", "details.product.images", "deliveries"])
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "cancel"]
            ])
            ->orderBy("shop_id")
            ->get();
        return view('regular.tabs.order-tab', compact("pendings", "processed", "sents", "finished", "cancelled"));
    }

    public function changeTab(Request $request)
    {

        $data = Order::with(["shop", "details.product.images", "deliveries"])
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", $request->get("type")]
            ])
            ->orderBy("shop_id")
            ->get();
        return response()->json($data);
    }

    public function getAddAddressForm(Request $request)
    {
        return response()->json([
            "content" => view("regular.modals.newAddressForm")->render()
        ]);
    }
    public function getUpdateAddAddressForm(Request $request)
    {
        $data = Address::find($request->get("id"));
        if ($request->get("type") == "shop") $data = Shop::find($request->get("id"));
        return response()->json([
            "content" => view("regular.modals.newAddressForm")->render(),
            "data" => $data
        ]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->get("name");
        $user->username = $request->get("username");
        $user->phoneNumber = $request->get("phoneNumber");
        $user->gender = $request->get("gender");

        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $ext = $file->getClientOriginalExtension();
            $filename = "avatar_" . time() . "." . $ext;
            $path = public_path() . '/profileimages';
            $file->move($path, $filename);
            $user->profilePicture = $filename;
        }
        $user->save();
        return redirect()->route('profile');
    }
    public function addNewAddress(Request $request)
    {
        $latlong = explode(",", $request->get("latlng"));
        $tags = explode("-", $request->get("tags"));
        if ($tags[0] == "update") {
            if ($tags[1] == "home") {
                $updatedAddress = Address::updateAddress($request->get("address"), $request->get("id"), $latlong);
                return response()->json(["data" => $updatedAddress, "type" => $tags]);
            }
            $updatedAddressShop = Address::updateShopAddress($request->get("address"), $request->get("id"), $latlong);
            return response()->json(["data" => $updatedAddressShop, "type" => "Update Shop"]);
        }
        $newAddress = Address::addNewAddress($request->get("address"), Auth::user()->id, $latlong);
        return response()->json(["data" => $newAddress]);
    }
    public function setCurAddr(Request $request)
    {
        DB::transaction(function () use ($request) {
            Address::where("user_id", Auth::user()->id)->update(['current' => false]);
            Address::where("id", $request->get("id"))->update(['current' => true]);

            Product::changeDistanceInCart($request->get("id"));
        });
        return response()->json(["status" => "OK"]);
    }
    public function deleteAddress(Request $request)
    {
        return response()->json(Address::find($request->get("id"))->delete());
    }
    public function topup(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->saldo = $user->saldo + $request->get("value");
        $user->save();
        return response()->json($user);
    }

    public function openAccount()
    {
        $exists = Shop::where("user_id", Auth::user()->id)->get();
        if (count($exists) > 0) {
            return redirect()->route('seller.index');
        }
        return view('auth.register-seller');
    }

    public function processSellerAcc(Request $request)
    {
        $request->validate(
            [
                'image' => 'required|image|mimes:jpg,png,jpeg|max:3096',
                'name' => 'required|string',
                'address' => 'required|string',
                'phoneNumber' => 'required|min:10|max:13'
            ],
            [
                "image.image" => "File bukan gambar!",
                "image.max" => "Ukuran gambar melebihi 3MB!",
                "name.required" => "Bagian nama tidak boleh dikosongi",
                "address.required" => "Bagian alamat tidak boleh dikosongi",
                "phoneNumber.min" => "Nomor telepon minimal 10 angka",
                "phoneNumber.max" => "Nomor telepon maksimal 13 angka",
                "phoneNumber.required" => "Nomor telepon tidak boleh dikosongi"
            ]
        );
        $latlong = explode(",", $request->get("latlng"));
        $file = $request->file("image");
        $ext = $file->getClientOriginalExtension();
        $filename = "shop_" . time() . "." . $ext;
        $path = public_path() . '/shopimages';
        $file->move($path, $filename);

        $newShop = DB::transaction(function () use ($request, $latlong, $filename) {
            $newShop = new Shop();
            $newShop->user_id = Auth::user()->id;
            $newShop->name = $request->get("name");
            $newShop->phoneNumber = $request->get("phoneNumber");
            $newShop->address = $request->get("address");
            $newShop->lat = $latlong[0];
            $newShop->long = $latlong[1];
            $newShop->logoImage = $filename;
            $newShop->save();

            $user = User::find(Auth::user()->id);
            $user->type = "seller";
            $user->save();

            return $newShop;
        });
        return view('merchant.product', ["shop" => $newShop]);
    }


    public function getAllRelatedShop(Request $request)
    {
        $orders = Order::where([
            ["user_id", Auth::user()->id],
        ])->get();
        return response()->json(compact("orders"));
    }

    public function getOrderDetail(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $orderID = $request->get("orderID");

            $order = Order::with(["details.product.images", "shop", "deliveries"])
                ->where("id", $orderID)
                ->first();

            return $order;
        });
        return response()->json($result);
    }

    public function orderFinish(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $orderID = $request->get("orderID");

            $order = Order::with(["details.product.images", "shop", "deliveries"])
                ->where("id", $orderID)
                ->first();

            $order->payment_status = "release";
            $order->payment_release_date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateString();
            $order->orderStatus = "done";
            $order->save();

            $income = new FinancialHistory();
            $income->shop_id = $order->shop_id;
            $income->income = $order->total;
            $income->date =  Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateString();
            $income->save();

            $shop = Shop::find($order->shop_id);
            $shop->saldo_release = $shop->saldo_release + $income->income;
            $shop->save();

            return true;
        });
        return response()->json($result);
    }

    public function giveRating(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $review = $request->get("review");
            $rating = $request->get("rating");
            $orderID = $request->get("orderID");

            $order = Order::with(["details.product.images"])
                ->where("id", $orderID)
                ->first();
            foreach ($order->details as $value) {
                $totalSold = ProductReview::where("product_id", $value->product_id)->count();
                $product = Product::find($value->product_id);
                $newAVG = $this->calculateNewAverageRating($product->rating, $totalSold, $rating);
                $product->rating = $newAVG;
                $product->save();
                if ($review != "") {
                    $newReview = new ProductReview();
                    $newReview->user_id = $order->user_id;
                    $newReview->product_id = $value->product_id;
                    $newReview->rating = $rating;
                    $newReview->review = $review;
                    $newReview->save();
                }
            }
        });
        return response()->json($result);
    }

    public function cancelOrder(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $orderID = $request->get("orderID");

            $order = Order::with(["details"])->where("id", $orderID)->first();
            $status = $order->orderStatus;

            if ($status != "new") {
                return false;
            }

            if ($order->payment_method == "saldo") {
                $user = User::find(Auth::user()->id);
                $user->saldo = $user->saldo + $order->total;
            }

            foreach ($order->details as $key => $value) {
                $product = Product::find($value->product_id);
                $product->stock = $product->stock + $value->qty;
                $product->status = ($product->stock == 0 ? "out of stock" : $product->status);
                $product->save();

                $prodHistory = new ProductStockHistory();
                $prodHistory->product_id = $value->product_id;
                $prodHistory->addition = $value->qty;
                $prodHistory->substraction = 0;
                $prodHistory->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
                $prodHistory->total_stock = $product->stock;
                $prodHistory->save();
            }
            $order->orderStatus = "cancel";
            $order->save();

            $newNotif = new Notification();
            $newNotif->header = "Pesanan anda dibatalkan!";
            $newNotif->content = "Pesanan anda telah berhasil dibatalkan.";
            $newNotif->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
            $newNotif->user_id = Auth::user()->id;
            $newNotif->save();

            $user = User::find($newNotif->user_id);
            $user->notify(new OrderNotification($newNotif->header, $newNotif->content, route("profile.order")));


            return true;
        });
        return response()->json($result);
    }

    private function calculateNewAverageRating($currentRating, $totalSold, $newRating)
    {
        return (($currentRating * $totalSold) + $newRating) / ($totalSold + 1);
    }

    public function userPushSubscribe(Request $request)
    {
        Log::info("SUBSCRIPTION PROCESS...");
        $this->validate($request, [
            'endpoint' => "required",
            "keys.auth" => "required",
            "keys.p256dh" => "required"
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = (Auth::guard("courier")->check() ? Courier::find(Auth::guard("courier")->user()->id) : User::find(Auth::user()->id));
        Log::info(Auth::guard("courier")->check());
        Log::info($user);
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true], 200);
    }

    // Maybe not used anymore
    public function generatePusherToken(Request $request)
    {
        if ($request->get("user_id") == $id = Auth::user()->id) {
            $beamsClient = new PushNotifications(array(
                "instanceId" => "1d20c86a-7a76-4cb2-b6ff-8053628e0303",
                "secretKey" => "C7C265C55D4DFDF7B7D5E6114C62E5BE0AB8716B00B7DB802DC4E35E3F2AD8DA",
            ));
            $token = $beamsClient->generateToken("$id");
            // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMWQyMGM4NmEtN2E3Ni00Y2IyLWI2ZmYtODA1MzYyOGUwMzAzLnB1c2hub3RpZmljYXRpb25zLnB1c2hlci5jb20iLCJzdWIiOiIxIiwiZXhwIjoxNzE1Nzg2MTQzfQ.E6jcUp-7EJbUrs-b_hKqNJW_Mxu-cmvt_JaXjnN6X2U";

            Log::info($token);
            return response()->json($token);
        } else {
            Log::info("Inconsistent User ID...");
            return response('Inconsistent request', 401);
        }
    }

    public function getLoggedInID(Request $request)
    {
        return response()->json(Auth::user()->id);
    }

    // Maybe not used anymore
    public function notificationDenied(Request $request)
    {
        $beamsClient = new PushNotifications(array(
            "instanceId" => "1d20c86a-7a76-4cb2-b6ff-8053628e0303",
            "secretKey" => "C7C265C55D4DFDF7B7D5E6114C62E5BE0AB8716B00B7DB802DC4E35E3F2AD8DA",
        ));
        $id = Auth::user()->id;
        $res = $beamsClient->deleteUser("$id");

        Log::info($id);
        return response()->json($res);
    }
}
