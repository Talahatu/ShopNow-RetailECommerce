<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shop;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class OrderController extends Controller
{
    public function checkoutCreate(Request $request)
    {
        $validate = $request->validate([
            "address" => "required",
            "payment" => "required",
        ]);
        $destinationAddressID = $request->get("address");
        $paymentMethod = $request->get("payment");
        $total = $request->get("total");

        $payment = ($paymentMethod == "ew" ? "saldo" : "cod");
        $result = Order::createOrder($destinationAddressID, $payment, $total);
        return response()->json(["data" => $result]);
    }

    public function ordersIndex()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view('merchant.orders', compact("shop"));
    }
    public function fetchRepopulate(Request $request)
    {
        $type = $request->get("type");
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $orders = Order::getOrder($shop->id, $type);
        return response()->json(["data" => $orders]);
    }

    public function fetchNewOrder()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $data = Order::getOrder($shop->id, "new");
        return response()->json(["data" => $data]);
    }
}
