<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

        return true;
    }
    public static function processCOD($delivery, $order, $image, $money)
    {
    }
}
