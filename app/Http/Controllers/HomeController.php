<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Chat;
use App\Models\ChatContent;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Pusher\Pusher;
use Pusher\PushNotifications\PushNotifications;

use function Ramsey\Uuid\v1;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::all();
        $recent = null;
        if (session()->has("rvp")) {
            $rvp = session('rvp', []);
            $recent = Product::with('images', 'brand', 'category')->whereIn("id", $rvp)->get();
        }
        return view('home', compact("categories", "recent"));
    }
    public function searchShow($query)
    {
        $var = "%$query%";
        $check = Product::where("name", "LIKE", $var)->first();
        $checkCate = Category::where("name", $query)->first("id");
        $checkBrand = Brand::where("name", $query)->first("id");
        if ($check) {
            $categories = Category::join("brand_categories AS bc", "bc.category_id", "categories.id")->where("brand_id", $check->brand_id)->get();
            $brands = Brand::join("brand_categories AS bc", "bc.brand_id", "brands.id")->where("category_id", $check->category_id)->get();
        } else {
            $categories = Category::all();
            $brands = Brand::all();
        }
        return view('regular.search', compact("query", "categories", "brands", "checkCate", "checkBrand"));
    }

    public function reregister()
    {
        User::deleteUser(Auth::user()->id);
        Cookie::queue(Cookie::forget(strtolower(config('app.name')) . '_session'));
        return redirect()->route('register')->with("verifyfailed", "Please re-register again!");
    }

    public function changeEmailShow()
    {
        return view('auth.email-change');
    }

    public function changeEmail(Request $request)
    {
        $email = $request->get("email");
        $user = User::find(Auth::user()->id);
        $user->email = $email;
        $user->email_verified_at = null;
        $user->save();
        $user->sendEmailVerificationNotification();
        return redirect()->route('verification.notice');
    }

    public function verifyLoggedEmail()
    {
        User::find(Auth::user()->id)->sendEmailVerificationNotification();
        return redirect()->route('verification.notice');
    }

    public function showShop($id)
    {
        $shop = Shop::with(["products.category", "products.images"])->where("id", $id)->first();
        return view('regular.shop', compact("shop"));
    }

    public function testing(Request $request)
    {
        // Not Working
        // Log::info("Initiate Notification...");
        // Notification::send(User::all(), new TestNotification("multiple"));
        // $user = User::find(2);
        // $user->notify(new TestNotification("single"));
        // Log::info("Notification send...");

        // Pusher Beam
        $beamsClient = new PushNotifications(array(
            "instanceId" => "41478210-9249-430a-8232-6659fa6e957b",
            "secretKey" => "D4BC56010FC6C07388DF9972A6FCD3773070758946FEE3416288610F0371D035",
        ));
        $id = "2";
        $beamsClient->publishToUsers(
            array("$id"),
            array(
                "web" => array("notification" => array(
                    "title" => "Hello User $id",
                    "body" => "Hello, World!",
                    "deep_link" => "http://127.0.0.1:8000/",
                )),
            )
        );
        return response()->json($id);
    }
}
