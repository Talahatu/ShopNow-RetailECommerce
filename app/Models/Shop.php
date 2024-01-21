<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = "shops";
    protected $fillable = [
        'name', 'description', 'address', 'lat', 'long', 'logoImage', 'saldo_pending', 'saldo_release', 'user_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, "shop_id", "id");
    }
    public function orders()
    {
        return $this->hasMany(Order::class, "shop_id", "id");
    }
    public function chats()
    {
        return $this->hasMany(Chat::class, "shop_id", "id");
    }
    public function owner()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
