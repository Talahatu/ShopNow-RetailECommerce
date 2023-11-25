<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
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
        return redirect()->route('profile.bio');
    }

    public function profileNotif()
    {
        return view('regular.tabs.notif-tab');
    }
    public function profileBio()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view('regular.tabs.profile-tab', compact("shop"));
    }
    public function profileOrder()
    {
        $pendings = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "new"]
            ])
            ->orderBy("shop_id")
            ->get();
        $processed = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "accepted"]
            ])
            ->orderBy("shop_id")
            ->get();
        $sents = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "sent"]
            ])
            ->orderBy("shop_id")
            ->get();
        $finished = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "done"]
            ])
            ->orderBy("shop_id")
            ->get();
        $cancelled = Order::with("shop", "details.product.images")
            ->where([
                ["user_id", Auth::user()->id],
                ["orderStatus", "cancel"]
            ])
            ->orderBy("shop_id")
            ->get();
        return view('regular.tabs.order-tab', compact("pendings", "processed", "sents", "finished", "cancelled"));
    }

    public function getAddAddressForm(Request $request)
    {
        return response()->json([
            "content" => view("regular.modals.newAddressForm")->render()
        ]);
    }
    public function getUpdateAddAddressForm(Request $request)
    {
        $data = Address::find($request->get("id"));
        if ($request->get("type") == "shop") $data = Shop::find($request->get("id"));
        return response()->json([
            "content" => view("regular.modals.newAddressForm")->render(),
            "data" => $data
        ]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
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
        $tags = explode("-", $request->get("tags"));
        if ($tags[0] == "update") {
            if ($tags[1] == "home") {
                $updatedAddress = Address::updateAddress($request->get("address"), $request->get("id"), $latlong);
                return response()->json(["data" => $updatedAddress, "type" => $tags]);
            }
            $updatedAddressShop = Address::updateShopAddress($request->get("address"), $request->get("id"), $latlong);
            return response()->json(["data" => $updatedAddressShop, "type" => "Update Shop"]);
        }
        $newAddress = Address::addNewAddress($request->get("address"), Auth::user()->id, $latlong);
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
    public function deleteAddress(Request $request)
    {
        return response()->json(Address::find($request->get("id"))->delete());
    }
    public function topup(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->saldo = $user->saldo + $request->get("value");
        $user->save();
        return response()->json($user);
    }
}
