<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
