<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = ["shop_id", "user_id", "orderDate", "orderStatus", "total", "destination_address", "destination_latitude", "destination_longitude", "payment_method", "payment_status", "payment_release_date", "refund_at", "total"];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, "order_id", "id");
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }

    public static function createOrder($addressID, $payment, $total)
    {
        try {
            $deletedCart = [];
            DB::transaction(function () use ($addressID, $payment, $total, &$deletedCart) {
                $totalCheckout = 0;
                $address = Address::find($addressID);
                $carts = Cart::join("products", "products.id", "cart.product_id")
                    ->join("shops", "shops.id", "products.shop_id")
                    ->where([
                        ["cart.user_id", Auth::user()->id],
                        ["cart.selected", "1"]
                    ])
                    ->orderBy("products.shop_id")
                    ->get(["products.shop_id"]);
                $allShopID = $carts->unique('shop_id')->pluck("shop_id")->values();

                foreach ($allShopID as $value) {
                    // Tiap Toko
                    $aggregateData = Cart::join("products", "products.id", "cart.product_id")
                        ->join("shops", "shops.id", "products.shop_id")
                        ->where("products.shop_id", $value)
                        ->where([
                            ["cart.user_id", Auth::user()->id],
                            ["cart.selected", "1"]
                        ])
                        ->groupBy("products.shop_id")
                        ->first([DB::raw("SUM(cart.qty*cart.price) AS cartTotal"), DB::raw("SUM(CalculateFeeByWeight(products.weight*cart.qty) + CalculateFeeByDistance(cart.distance)) AS shippingFee")]);
                    $total = $aggregateData->cartTotal + $aggregateData->shippingFee;
                    $totalCheckout += $total;
                    if ($totalCheckout > Auth::user()->saldo) {
                        throw new Exception("Saldo not enough.", 1);
                    }

                    $newOrder = new Order();
                    $newOrder->user_id = Auth::user()->id;
                    $newOrder->shop_id = $value;
                    $newOrder->order_date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateString();
                    $newOrder->orderStatus = 'new';
                    $newOrder->destination_address = $address->name;
                    $newOrder->destination_latitude = $address->lat;
                    $newOrder->destination_longitude = $address->long;
                    $newOrder->payment_method = $payment;
                    $newOrder->payment_status = "onhold";
                    $newOrder->total = $total;
                    $newOrder->subtotal = $aggregateData->cartTotal;
                    $newOrder->shippingFee = $aggregateData->shippingFee;
                    $newOrder->orderID = Order::getOrderID();
                    $newOrder->save();

                    $cartProductsByShop = Cart::join("products", "products.id", "cart.product_id")
                        ->join("shops", "shops.id", "products.shop_id")
                        ->where("products.shop_id", $value)
                        ->where([
                            ["cart.user_id", Auth::user()->id],
                            ["cart.selected", "1"]
                        ])
                        ->get(["cart.id", "cart.product_id", "cart.qty", "cart.price", DB::raw("cart.qty * cart.price AS subtotal"), "cart.distance"]);
                    foreach ($cartProductsByShop as $item) {
                        $newDetail = new OrderDetail();
                        $newDetail->order_id = $newOrder->id;
                        $newDetail->product_id = $item->product_id;
                        $newDetail->qty = $item->qty;
                        $newDetail->price = $item->price;
                        $newDetail->subtotal = $item->subtotal;
                        $newDetail->distance = $item->distance;
                        $newDetail->save();

                        $cart = Cart::find($item->id);
                        $cart->delete();

                        $product = Product::find($item->product_id);

                        if ($product->stock < $item->qty) {
                            throw new Exception("Quantity exceed item stock", 1);
                        }

                        $product->stock = $product->stock - $item->qty;
                        $product->status = ($product->stock == 0 ? "out of stock" : $product->status);
                        $product->save();



                        $prodHistory = new ProductStockHistory();
                        $prodHistory->product_id = $item->product_id;
                        $prodHistory->addition = 0;
                        $prodHistory->substraction = $item->qty;
                        $prodHistory->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
                        $prodHistory->save();

                        array_push($deletedCart, $item->id);
                    }
                }
                if ($payment == "saldo") {
                    $user = User::find(Auth::user()->id);
                    $user->saldo = $user->saldo - $total;
                    $user->save();
                }

                $newNotif = new Notification();
                $newNotif->header = "New order created!";
                $newNotif->content = "Your order has been succesfully created. Waiting for seller acceptance and packaging";
                $newNotif->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
                $newNotif->user_id = Auth::user()->id;
                $newNotif->save();
            });
            return $deletedCart;
        } catch (\Throwable $e) {
            return [false, $e->getMessage()];
        }
    }
    private function getOrderID()
    {
        return "OD" . now()->format("Ymds");
    }

    public static function getOrder($shopID, $type)
    {
        return Order::join("order_details AS od", "od.order_id", "orders.id")
            ->where([
                ["orders.shop_id", $shopID],
                ["orders.orderStatus", $type]
            ])->get(["orders.id", "orders.destination_address", DB::raw("ROUND(od.distance,0) AS distance"), "orders.payment_method", "orders.orderID"]);
    }
}
