<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'phoneNumber', 'profilePicture', 'type', 'gender', 'saldo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function addresses()
    {
        return $this->hasMany(Address::class, "user_id", "id");
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, "user_id", "id");
    }
    public function chats()
    {
        return $this->hasMany(Chat::class, "user_id", "id");
    }
    public function shop()
    {
        return $this->hasOne(Shop::class, "user_id", "id");
    }
    public function orders()
    {
        return $this->hasMany(Order::class, "user_id", "id");
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, "user_id", "id");
    }


    //functions
    public static function deleteUser($id)
    {
        return User::find($id)->delete();
    }

    public static function checkVerifyEmail(User $u)
    {
        return $u->hasVerifiedEmail();
    }
}
