<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class UserController extends Controller
{
    //
    public function profile()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view('regular.profile', compact("shop"));
    }

    public function getAddAddressForm(Request $request)
    {
        return response()->json([
            "content" => view("regular.modals.newAddressForm")->render()
        ]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->get("name");
        $user->username = $request->get("username");
        $user->phoneNumber = $request->get("phoneNumber");
        $user->gender = $request->get("gender");

        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $ext = $file->getClientOriginalExtension();
            $filename = "avatar_" . time() . "." . $ext;
            $path = public_path() . '/profileimages';
            $file->move($path, $filename);
            $user->profilePicture = $filename;
        }
        $user->save();
        return redirect()->route('profile');
    }
    public function addNewAddress(Request $request)
    {
        $latlong = explode(",", $request->get("latlng"));
        $newAddress = new Address();
        $newAddress->user_id = Auth::user()->id;
        $newAddress->name = $request->get("address");
        $newAddress->lat = $latlong[0];
        $newAddress->long = $latlong[1];
        $newAddress->current = false;
        $newAddress->save();
        return response()->json(["data" => $newAddress]);
    }
    public function setCurAddr(Request $request)
    {
        DB::transaction(function () use ($request) {
            Address::where("user_id", Auth::user()->id)->update(['current' => false]);
            Address::where("id", $request->get("id"))->update(['current' => true]);
        });
        return response()->json(["status" => "OK"]);
    }
}
