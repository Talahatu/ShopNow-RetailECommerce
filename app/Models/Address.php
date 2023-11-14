<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = "addresses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'lat', 'long', 'current',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function addNewAddress($address, $id, $latlong)
    {
        $newAddress = new Address();
        $newAddress->user_id = $id;
        $newAddress->name = $address;
        $newAddress->lat = $latlong[0];
        $newAddress->long = $latlong[1];
        $newAddress->current = false;
        $newAddress->save();
        return $newAddress;
    }
    public static function updateAddress($address, $id, $latlong)
    {
        $newAddress = Address::find($id);
        $newAddress->name = $address;
        $newAddress->lat = $latlong[0];
        $newAddress->long = $latlong[1];
        $newAddress->save();
        return $newAddress;
    }
    public static function updateShopAddress($address, $id, $latlong)
    {
        $newAddress = Shop::find($id);
        $newAddress->address = $address;
        $newAddress->lat = $latlong[0];
        $newAddress->long = $latlong[1];
        $newAddress->save();
        return $newAddress;
    }
}
