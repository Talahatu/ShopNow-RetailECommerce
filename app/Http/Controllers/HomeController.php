<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

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
        return view('home', compact("categories"));
    }

    public function reregister()
    {
        User::deleteUser(Auth::user()->id);
        Cookie::queue(Cookie::forget(strtolower(config('app.name')) . '_session'));
        return redirect()->route('register')->with("verifyfailed", "Please re-register again!");
    }
}
