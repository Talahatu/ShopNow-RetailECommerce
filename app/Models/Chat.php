<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = "chats";

    protected $fillable = ["user_id", "shop_id"];


    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function contents()
    {
        return $this->hasMany(ChatContent::class, "chat_id", "id");
    }
}
