<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function courierIndex()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $couriers = Courier::where("shop_id", $shop->id)->get();
        return view("merchant.couriers", compact("couriers", "shop"));
    }

    public function create()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view("merchant.courier-create", compact("shop"));
    }
}
