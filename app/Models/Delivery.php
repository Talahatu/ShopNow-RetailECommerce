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
}
