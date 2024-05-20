<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Delivery;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStockHistory;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\OrderNotification;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\PushNotifications\PushNotifications;

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

    public function acceptOrder(Request $request)
    {
        $orderID = $request->get("orderID");
        DB::transaction(function () use ($orderID) {
            $datetime = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateString();
            $order = Order::find($orderID);
            $order->orderStatus = "accepted";
            $order->accept_date = $datetime;
            $order->save();

            $user = User::find($order->user_id);
            $user->notify(new OrderNotification("Pesanan Anda Diterima", "Pesanan anda " . $order->orderID . " telah diterima oleh seller pada $datetime. Pesanan anda saat ini sedang diproses.", route("profile.order")));

            $newNotif = new Notification();
            $newNotif->header = "Pemberitahuan Pesanan";
            $newNotif->content = "Pesanan anda $order->orderID telah diterima oleh seller pada $datetime. Pesanan anda saat ini sedang diproses.";
            $newNotif->date = $datetime;
            $newNotif->user_id = $order->user_id;
            $newNotif->save();
        });
        return response()->json($orderID);
    }
    public function rejectOrder(Request $request)
    {
        $orderID = $request->get("orderID");
        $reason = $request->get("reason");
        DB::transaction(function () use ($orderID, $reason) {
            $order = Order::find($orderID);
            $order->orderStatus = "cancel";
            $order->save();

            $datetime = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();

            $orderedProducts = OrderDetail::where("order_id", $order->id)->get();

            foreach ($orderedProducts as $key => $value) {
                $products = Product::where('id', $value->product_id)->first();
                $products->stock = $products->stock + $value->qty;
                $products->status = "live";
                $products->save();

                $history = new ProductStockHistory();
                $history->product_id = $value->product_id;
                $history->addition = $value->qty;
                $history->substraction = 0;
                $history->date = $datetime;
                $history->save();
            }


            $user = User::find($order->user_id);
            $user->notify(new OrderNotification("Pesanan Anda Ditolak", "Pesanan anda " . $order->orderID . " ditolak oleh seller pada $datetime karena $reason.", route("profile.order")));

            $newNotif = new Notification();
            $newNotif->header = "Pesanan anda ditolak oleh seller";
            $newNotif->content = "Pesanan anda ditolak oleh seller pada $datetime karena $reason.";
            $newNotif->date = $datetime;
            $newNotif->user_id = $order->user_id;
            $newNotif->save();
        });
        return response()->json($orderID);
    }
    public function detailOrder(Request $request)
    {
        $orderID = $request->get("orderID");
        $result = DB::transaction(function () use ($orderID) {
            $order = Order::with("user")->where("id", $orderID)->first();
            $orderDetail = OrderDetail::join("products", "products.id", "order_details.product_id")
                ->join("images", "images.product_id", "products.id")
                ->join("orders", "orders.id", "order_details.order_id")
                ->where("order_details.order_id", $orderID)
                ->get(["products.name AS pname", "images.name AS iname", "products.sku", "order_details.qty", "order_details.price", "order_details.subtotal", "orders.distance"]);
            return ["info" => $order, "products" => $orderDetail];
        });
        return response()->json($result);
    }

    public function getIDs(Request $request)
    {
        $order = Order::find($request->get("orderID"));
        return response()->json(["userID" => $order->user_id, "shopID" => $order->shop_id]);
    }
    public function pickCourier(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $courierID = $request->get("courierID");
            $orderID = $request->get("orderID");
            $startDate = $request->get("deliveryDate");
            $operational = $request->get("operational");

            $order = Order::with(["shop", "user", "shop.couriers" => function ($query) use ($courierID) {
                $query->where("id", $courierID)->first();
            }])->where("id", $orderID)->first();

            $newDelivery = new Delivery();
            $newDelivery->order_id = $orderID;
            $newDelivery->courier_id = $courierID;
            $newDelivery->start_date = $startDate;
            $newDelivery->status = "new";
            $newDelivery->resi = $this->generateResiNumber($order->shop->name, $order->user->name, $order->shop->couriers[0]->name);
            $newDelivery->feeAssigned = $operational;
            $newDelivery->save();

            $order->orderStatus = "sent";
            $order->save();

            $newNotif = new Notification();
            $newNotif->header = "Pesanan anda telah dikirim ke kurir";
            $newNotif->content = "Pesanan anda diberikan ke kurir, menunggu kurir mengambil barang dari seller";
            $newNotif->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
            $newNotif->user_id = $order->user_id;
            $newNotif->save();

            $shopID = $order->shop_id;
            $userID = $order->user_id;
            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
            );
            $pusher = new \Pusher\Pusher(
                'c58a82be41ea6c60c1d7',
                '8264fc21e2b5035cc329',
                '1716744',
                $options
            );

            $data['message'] = "Pesanan anda dengan nomor $order->orderID diberikan ke kurir, menunggu kurir mengambil barang dari seller";
            $data["key"] = "sentToCourier";
            $data["time"] = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();

            // regular-seller
            $pusher->trigger('private-my-channel-' . $userID . '-' . $shopID, 'client-notif', $data);

            return $order->id;
        });
        return response()->json($result);
    }

    public function getPaymentType(Request $request)
    {
        $orderID = $request->get("orderID");
        $order = Order::find($orderID);
        return response()->json(["type" => $order->payment_method]);
    }

    private function generateResiNumber($toko, $user, $courier)
    {
        // Inisial Nama Toko + Inisial Nama Pelanggan + Inisial Nama Kurir + Tanggal Pengiriman (Ymds)
        return strtoupper($toko[0]) . strtoupper($user[0]) . strtoupper($courier[0]) . now()->format("Ymds");
    }

    public function deliveryDetail(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $orderID = $request->get("orderID");

            $order = Order::with(["deliveries.courier"])->where("id", $orderID)->first();

            return $order;
        });

        return response()->json($result);
    }

    public function deliveryDone(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $deliveryID = $request->get("deliveryID");
            $delivery = Delivery::find($deliveryID);
            $delivery->status = "done";
            $delivery->save();
            return true;
        });
        return response()->json($result);
    }
}
