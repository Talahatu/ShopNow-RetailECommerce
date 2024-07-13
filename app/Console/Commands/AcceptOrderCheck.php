<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStockHistory;
use App\Models\User;
use App\Notifications\OrderNotification;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class AcceptOrderCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:acceptedOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Determine auto cancellation based on order acceptance date by seller';

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
        $acceptedOrder = Order::where("orderStatus", "accepted")->orWhere("orderStatus", "sent")->get();
        foreach ($acceptedOrder as $key => $value) {
            Log::info("Run successfully!!!");
            $today = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();

            // 2PM
            $timeLimit = "14";
            $acceptedHour = Carbon::createFromDate($value->accept_date)->format("H");


            if ($acceptedHour < $timeLimit) {
                // dealine 1 day
                Log::info("Before 2PM");

                $deadline = Carbon::createFromDate($value->accept_date);
                $deadline->addDay();
                $deadline = Carbon::parse($deadline)->startOfDay();
            } else {
                // deadline 2 day!!
                Log::info("After 2PM");
                $deadline = Carbon::createFromDate($value->accept_date);
                $deadline->addDays(2);
                $deadline = Carbon::parse($deadline)->startOfDay();
            }

            Log::info($deadline);
            $delivery = Delivery::where("order_id", $value->id)->first();

            // delivery == null means order not sent yet 
            // arrive_date == null means buyer didn't received their order yet
            if ($today > $deadline &&  ($delivery == null || $delivery->arrive_date == null)) {
                Log::info("REJECT ORDER");
                $this->rejectOrder($value, $today);
            } else {
                Log::info("CONTINUE");
            }
        }
        return 0;
    }

    private function rejectOrder($order, $today)
    {
        DB::transaction(function () use ($order, $today) {
            $reason = "Pesanan melewati batas waktu pengiriman!";
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
