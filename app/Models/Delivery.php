<?php

namespace App\Models;

use App\Notifications\DeliveryNotification;
use App\Notifications\OrderNotification;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class Delivery extends Model
{
    use HasFactory;

    protected $table = "delivery";

    public function courier()
    {
        return $this->belongsTo(Courier::class, "courier_id", "id");
    }

    public function order()
    {
        return $this->belongsTo(Order::class, "order_id", "id");
    }

    public static function processSaldo($delivery, $order, $image)
    {
        $delivery->arrive_date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
        $delivery->status = "done";

        $filename = hrtime(true) . "-proof" . "." . $image->getClientOriginalExtension();
        $path = public_path() . "/deliveryProof";
        $image->move($path, $filename);

        $delivery->proofImage = $filename;
        $delivery->save();

        Delivery::courierFinishNotification($order);

        return true;
    }
    public static function processCOD($delivery, $order, $image)
    {
        $delivery->arrive_date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
        // $delivery->status = "done";

        $filename = hrtime(true) . "-proof" . "." . $image->getClientOriginalExtension();
        $path = public_path() . "/deliveryProof";
        $image->move($path, $filename);

        $delivery->proofImage = $filename;
        $delivery->save();

        // Changed because operational fee is not separated with delivery!
        // if ($money > 0) {
        //     $newFeeHistory = new CourierFeeHistory();
        //     $newFeeHistory->courier_id = $delivery->courier_id;
        //     $newFeeHistory->nominal = $money;
        //     $newFeeHistory->description = "Uang saku digunakan kurir dalam proses transaksi pada pesanan nomor $order->orderID";
        //     $newFeeHistory->type = "used"; 
        //     $newFeeHistory->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
        //     $newFeeHistory->save();
        // }

        Delivery::courierFinishNotification($order);

        return true;
    }

    private function courierFinishNotification($order)
    {
        $newNotif = new Notification();
        $newNotif->header = "Pesanan telah sampai!";
        $newNotif->content = "Pesanan anda telah berhasil sampai di lokasi tujuan!";
        $newNotif->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
        $newNotif->user_id = $order->user_id;
        $newNotif->save();

        $user = User::find($order->user_id);
        $user->notify(new OrderNotification("Pesanan Telah Sampai", "Pesanan anda telah berhasil sampai di lokasi tujuan!", route("profile.order")));

        $user = User::find($order->shop->user_id);
        $user->notify(new DeliveryNotification("Pengiriman Telah Selesai", "Pesanan nomor " . $order->orerID . " telah sampai alamat tujuan pengiriman.", route("order.index")));
    }
}
