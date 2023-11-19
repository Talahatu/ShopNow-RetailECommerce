<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

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
    public function showProduct($id)
    {
        $prod = Product::find($id);
        $prod->load("category", "brand", "images", "shop");
        $relatedProd = Product::where("id", "!=", $id)
            ->where(function ($query) use ($prod) {
                $query->where("category_id", $prod->category_id)->orWhere("brand_id", $prod->brand_id);
            })->get();
        if (session()->has("rvp")) {
            if (!in_array($id, session('rvp'))) {
                if (count(session('rvp')) == 4) {
                    $rvp = session('rvp');
                    array_shift($rvp);
                    session(["rvp" => $rvp]);
                }
                session()->push("rvp", $id);
            }
        } else {
            session()->push("rvp", $id);
        }

        return view("regular.product-info", ["data" => $prod, "related" => $relatedProd]);
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
}
