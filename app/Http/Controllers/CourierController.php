<?php

namespace App\Http\Controllers;

use App\Mail\NewEmailMail;
use App\Models\Courier;
use App\Models\CourierFeeHistory;
use App\Models\Delivery;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shop;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use function Ramsey\Uuid\v1;

class CourierController extends Controller
{
    use AuthenticatesUsers;

    public function courierIndex()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $couriers = Courier::with(["deliveries"])
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

            $this->newCourierSendMail($email, $newCourier);
            $newCourier->password = bcrypt($newCourier->password);
            $newCourier->save();
        });
        return redirect()->route("courier.index");
    }

    protected function newCourierSendMail($mailAddress, $data)
    {
        Mail::to($mailAddress)->send(new NewEmailMail($data));
    }

    public function showLoginPage()
    {
        if (Auth::guard("courier")->check()) {
            return redirect()->route('courier.home');
        }
        return view('auth.login-courier');
    }

    // Login Process Start
    public function loginAttempt(Request $request)
    {
        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($result = $this->attemptLogin($request)) {

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            return $this->sendLoginResponse($request);
        }
        // dd($result);
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);


        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended(route('courier.home'));
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::guard('courier')->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    public function username()
    {
        return 'username';
    }

    public function logout(Request $request)
    {
        Auth::guard("courier")->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route("courier.show.login");
    }

    protected function guard()
    {
        return Auth::guard("courier");
    }

    // Login Process End

    public function courierHome()
    {
        $newDeliveries = Delivery::with(["order"])
            ->where("courier_id", Auth::guard("courier")->user()->id)
            ->where("status", "new")
            ->get();
        $currentDeliveries = Delivery::with(["order"])
            ->where("courier_id", Auth::guard("courier")->user()->id)
            ->where("status", "progress")
            ->get();
        return view('courier.index', compact("newDeliveries", "currentDeliveries"));
    }

    public function courierHistory()
    {
        $finishDeliveries = Delivery::with(["order"])
            ->where("courier_id", Auth::guard("courier")->user()->id)
            ->where("status", "done")
            ->get();
        return view("courier.history", compact("finishDeliveries"));
    }

    public function courierFeeHistory()
    {
        $histories = CourierFeeHistory::where("courier_id", Auth::guard("courier")->user()->id)->get();
        return view("courier.feeHistory", compact("histories"));
    }

    public function getAllByShop()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $couriers = Courier::with("deliveries")
            ->where("shop_id", $shop->id)->get();
        return response()->json(compact("shop", "couriers"));
    }

    public function fetchCourier(Request $request)
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $data = Courier::where([
            ["shop_id", $shop->id],
            ["name", "LIKE", "%" . $request->get("searchTerm") . "%"],
        ])->get(["name", "id"]);
        return response()->json(["data" => $data, "term" => $request->get("searchTerm")]);
    }

    public function getDetail(Request $request)
    {
        $orderID = $request->get("orderID");
        $order = Order::with(["deliveries", "details", "shop", "user"])->where("id", $orderID)->first();
        return response()->json($order);
    }

    public function pickupItems(Request $request)
    {
        $orderID = $request->get("orderID");
        $deliveryID = $request->get("deliveryID");

        $result = DB::transaction(function () use ($orderID, $deliveryID) {
            $takenDelivery = Delivery::find($deliveryID);
            $takenDelivery->status = "progress";
            $takenDelivery->pickup_date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
            $takenDelivery->save();

            $currentOrder = Order::find($orderID);

            $newNotif = new Notification();
            $newNotif->header = "Pemberitahuan Pesanan";
            $newNotif->content = "Pesanan anda telah diterima oleh kurir dan saat ini sedang dalam perjalanan.";
            $newNotif->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
            $newNotif->user_id = $currentOrder->user_id;
            $newNotif->save();

            $shopID = $currentOrder->shop_id;
            $userID = $currentOrder->user_id;
            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
            );
            $pusher = new \Pusher\Pusher(
                'c58a82be41ea6c60c1d7',
                '8264fc21e2b5035cc329',
                '1716744',
                $options
            );

            $data['message'] = "Pesanan anda dengan nomor $currentOrder->orderID telah diterima oleh kurir dan saat ini sedang dalam perjalanan";
            $data["key"] = "courierPickUp";
            $data["time"] = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();

            // regular-seller
            $pusher->trigger('private-my-channel-' . $userID . '-' . $shopID, 'client-notif', $data);

            return ["startDate" => $takenDelivery->start_date, "orderID" => $currentOrder->orderID, "id" => $orderID, "deliveryID" => $deliveryID, "address" => $currentOrder->destination_address];
        });
        return response()->json($result);
    }

    public function deliveryFinish(Request $request)
    {
        $orderID = $request->get("orderID");
        $deliveryID = $request->get("deliveryID");
        $type = $request->get("type");
        $moneyUsed = $request->get("moneyUsed");
        $image = $request->file("file");

        $result = DB::transaction(function () use ($orderID, $deliveryID, $type, $moneyUsed, $image) {
            $delivery = Delivery::find($deliveryID);
            $order = Order::find($orderID);
            if ($type == "cod") {
                Delivery::processCOD($delivery, $order, $image, $moneyUsed);
            } else {
                Delivery::processSaldo($delivery, $order, $image);
            }
            return true;
        });

        return response()->json($result);
    }

    public function courierWithdraw(Request $request)
    {
        $amount = $request->get("amount");

        $result = DB::transaction(function () use ($amount) {
            $courier = Courier::find(Auth::guard("courier")->user()->id);
            $courier->operationalFee = $courier->operationalFee - $amount;
            $courier->save();

            $newHistory = new CourierFeeHistory();
            $newHistory->courier_id = $courier->id;
            $newHistory->nominal = $amount;
            $newHistory->description = "Kurir $courier->name berhasil melakukan penarikan uang saku sebesar Rp " . number_format($amount, 0, '.', ',');
            $newHistory->type = "withdraw";
            $newHistory->date = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();
            $newHistory->save();

            return $courier->operationalFee;
        });
        return response()->json($result);
    }
}
