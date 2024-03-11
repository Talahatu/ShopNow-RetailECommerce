<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierFeeHistory extends Model
{
    use HasFactory;

    protected $table = "courier_fee_histories";

    public function courier()
    {
        return $this->belongsTo(Courier::class, "courier_id", "id");
    }
}
