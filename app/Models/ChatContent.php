<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatContent extends Model
{
    use HasFactory;

    protected $table = "chat_content";

    protected $fillable = ["chat_id", "date", "content", "sender"];

    public function chat()
    {
        return $this->belongsTo(Chat::class, "chat_id", "id");
    }
}
