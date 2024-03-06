<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Authenticatable
{
    use HasFactory;

    protected $table = "courier";

    protected $fillable = [
        'name', 'email', 'username', 'password', "shop_id", "operationalFee"
    ];


    public function deliveries()
    {
        return $this->hasMany(Delivery::class, "courier_id", "id");
    }
    public function shopOwner()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }

    public function filteredDeliveries($type)
    {
        return $this->deliveries()->where("status", $type)->get();
    }
}
