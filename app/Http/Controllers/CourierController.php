<?php

namespace App\Http\Controllers;

use App\Mail\NewEmailMail;
use App\Models\Courier;
use App\Models\Shop;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CourierController extends Controller
{
    public function courierIndex()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $couriers = Courier::with("deliveries")
            ->where("shop_id", $shop->id)->get();
        return view("merchant.couriers", compact("couriers", "shop"));
    }

    public function create()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view("merchant.courier-create", compact("shop"));
    }

    public function store(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $name = $request->get("name");
            $email = $request->get("email");

            $shop = Shop::where("user_id", Auth::user()->id)->first();

            $newCourier = new Courier();
            $newCourier->shop_id = $shop->id;
            $newCourier->name = $name;
            $newCourier->email = $email;
            $newCourier->username = ucwords($name[0]) . ucwords($shop->name[0]) . Carbon::now(new DateTimeZone("Asia/Jakarta"))->format('dmY');
            $newCourier->password = random_int(100000, 999999);
            $newCourier->operationalFee = 0;
            $newCourier->save();

            $this->newCourierSendMail($email, $newCourier);
        });
        return redirect()->route("courier.index");
    }

    protected function newCourierSendMail($mailAddress, $data)
    {
        Mail::to($mailAddress)->send(new NewEmailMail($data));
    }
}
