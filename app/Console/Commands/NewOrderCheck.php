<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStockHistory;
use App\Models\User;
use App\Notifications\OrderNotification;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewOrderCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:newOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if new order is accepted by seller in 1 day timeframe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Run successfully!!!");

        $newOrder = Order::where("orderStatus", "new")->get();
        foreach ($newOrder as $key => $value) {
            $today = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
            $deadline = Carbon::createFromDate($value->order_date);
            $deadline->addDay();
            Log::info("Run successfully!!!");
            if ($today < $deadline) {
                // No order rejection by time limits
                Log::info("Before Deadline");
            } else {
                // Reject order automatically because pass the time limit!!
                Log::info("After Deadline");
                DB::transaction(function () use ($value, $today) {
                    $reason = "Pesanan tidak direspon oleh seller dalam jangka waktu 1 hari!";
                    $order = $value;
                    $order->orderStatus = "cancel";
                    $order->cancel_date = $today;
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
                        $history->total_stock = $products->stock;
                        $history->save();
                    }


                    $user = User::find($order->user_id);
                    $user->notify(new OrderNotification("Pesanan Dibatalkan", "Pesanan anda " . $order->orderID . " dibatalkan pada $today karena $reason.", route("profile.order")));
                    if ($order->payment_method == "saldo") {
                        $user->saldo = $user->saldo + $order->total;
                        $user->save();
                    }

                    $newNotif = new Notification();
                    $newNotif->header = "Pesanan anda dibatalkan secara otomatis";
                    $newNotif->content = "Pesanan anda dibatalkan pada $datetime karena $reason.";
                    $newNotif->date = $datetime;
                    $newNotif->user_id = $order->user_id;
                    $newNotif->save();
                });
            }
        }

        return 0;
    }
}
