<?php

namespace App\Http\Controllers;

use App\Mail\NewEmailMail;
use App\Models\Courier;
use App\Models\CourierFeeHistory;
use App\Models\Delivery;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\DeliveryNotification;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDO;

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

            $this->newCourierSendMail($email, $newCourier);

            $newCourier->password = bcrypt($newCourier->password);
            $newCourier->save();
        });
        return redirect()->route("courier.index");
    }

    public function showUpdatePage($id)
    {
        $courier = Courier::find($id);
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view("merchant.courier-update", compact("courier", "shop"));
    }
    public function update(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $name = $request->get("name");
            $email = $request->get("email");
            $id = $request->get("dia");

            $newCourier = Courier::find($id);
            $newCourier->name = $name;
            $newCourier->email = $email;
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
            ->whereNotNull("pickup_date")
            ->whereNull("arrive_date")
            ->get();
        return view('courier.index', compact("newDeliveries", "currentDeliveries"));
    }

    public function courierHistory()
    {
        $finishDeliveries = Delivery::with(["order"])
            ->where("courier_id", Auth::guard("courier")->user()->id)
            ->where("arrive_date", "!=", null)
            ->orderBy("arrive_date", "desc")
            ->orderByRaw("CASE WHEN status='progress' THEN 0 ELSE 1 END")
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
        $couriers = Courier::with(["deliveries" => function ($query) {
            $query->where("status", "!=", "done");
        }])
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

    // Changes to new page
    // public function getDetail(Request $request)
    // {
    //     $orderID = $request->get("orderID");
    //     $order = Order::with(["deliveries", "details", "shop", "user"])->where("id", $orderID)->first();
    //     return response()->json($order);
    // }
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

            $user = User::find($currentOrder->user_id);

            $user->notify(new DeliveryNotification($newNotif->header, $newNotif->content, route("profile.order")));

            return ["startDate" => $takenDelivery->start_date, "orderID" => $currentOrder->orderID, "id" => $orderID, "deliveryID" => $deliveryID, "address" => $currentOrder->destination_address];
        });
        return response()->json($result);
    }

    public function deliveryFinish(Request $request)
    {
        $orderID = $request->get("orderID");
        $deliveryID = $request->get("deliveryID");
        $type = $request->get("type");
        $image = $request->file("file");

        $result = DB::transaction(function () use ($orderID, $deliveryID, $type, $image) {
            $delivery = Delivery::find($deliveryID);
            $order = Order::with(["shop"])->where("id", $orderID)->first();
            if ($type == "cod") {
                Delivery::processCOD($delivery, $order, $image);
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

    public function deliveryDetail($orderID, $deliveryID)
    {
        $result = DB::transaction(function () use ($orderID, $deliveryID) {
            $order = Order::with(["deliveries", "details", "shop", "user"])->where("id", $orderID)->first();
            return $order;
        });
        return view("courier.deliveryDetail", ["order" => $result]);
    }

    public function deleteCourier($id)
    {
        $result = DB::transaction(function () use ($id) {
            $courier = Courier::find($id);

            $deliveries = Delivery::where("courier_id", $id)->where("status", "!=", "done")->get();
            if (count($deliveries) > 0) {
                return false;
            }
            $courier->delete();
            return true;
        });
        return response()->json($result);
    }

    public function getDeliveriesPosition(Request $request)
    {
        $id = Auth::guard("courier")->user()->id;
        $currentDeliveries = Delivery::with(["order"])
            ->where("courier_id", $id)
            ->whereNotNull("pickup_date")
            ->whereNull("arrive_date")
            ->get();
        $shopCoord = Courier::with(["shopOwner"])->where('id', $id)->first();
        return response()->json(compact("currentDeliveries", "shopCoord"));
    }

    public function nearDestination(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $orderID = $request->get("orderID");
            $order = Order::find($orderID);
            $user = User::find($order->user_id);
            $user->notify(new DeliveryNotification("Kurir telah sampai di lokasi anda!", "Kurir telah sampai di lokasi alamat rumah anda, mohon terima dan selesaikan pesanan setelah mengkonfirmasi pesanan", route("profile.order")));
            return true;
        });
        return response()->json($result);
    }
}
