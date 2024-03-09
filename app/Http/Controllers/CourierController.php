<?php

namespace App\Http\Controllers;

use App\Mail\NewEmailMail;
use App\Models\Courier;
use App\Models\Delivery;
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
        return view('courier.index', compact("newDeliveries"));
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
        $order = Order::with(["deliveries", "details"])->where("id", $orderID)->first();
        return response()->json($order);
    }
}
