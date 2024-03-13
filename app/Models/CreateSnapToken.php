<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;

class CreateSnapToken extends Model
{
    use HasFactory;

    public static function GenerateSnapTokenTopUp($value)
    {
        $params = [
            'transaction_details' => [
                "order_id" => uniqid(),
                'gross_amount' => $value
            ],
            'customer_details' => [
                "first_name" => Auth::user()->name,
                "email" => Auth::user()->email,
                "phone" => Auth::user()->phoneNumber
            ]
        ];

        return Snap::getSnapToken($params);
    }
}
