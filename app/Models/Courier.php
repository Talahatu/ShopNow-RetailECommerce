<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Courier extends Authenticatable
{
    use HasFactory, HasPushSubscriptions, Notifiable;

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

    public function sakuHistories()
    {
        return $this->hasMany(CourierFeeHistory::class, "courier_id", "id");
    }
}
