<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Image;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        return view('merchant.product', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $exists = Shop::where("user_id", Auth::user()->id)->get();
        // if (count($exists) > 0) {
        //     return redirect()->route('seller.index');
        // }
        // return view('auth.register-seller');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreShopRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate(
        //     [
        //         'image' => 'required|image|mimes:jpg,png,jpeg|max:3096',
        //         'name' => 'required|string',
        //         'address' => 'required|string',
        //         'phoneNumber' => 'required'
        //     ],
        //     [
        //         "image.image" => "its not an image!",
        //         "image.max" => "Image size exceed 3MB!"
        //     ]
        // );
        // $latlong = explode(",", $request->get("latlng"));
        // $file = $request->file("image");
        // $ext = $file->getClientOriginalExtension();
        // $filename = "shop_" . time() . "." . $ext;
        // $path = public_path() . '/shopimages';
        // $file->move($path, $filename);

        // $newShop = "";
        // DB::transaction(function () use ($request, $latlong, $filename) {
        //     $newShop = new Shop();
        //     $newShop->user_id = Auth::user()->id;
        //     $newShop->name = $request->get("name");
        //     $newShop->phoneNumber = $request->get("phoneNumber");
        //     $newShop->address = $request->get("address");
        //     $newShop->lat = $latlong[0];
        //     $newShop->long = $latlong[1];
        //     $newShop->logoImage = $filename;
        //     $newShop->save();

        //     $user = User::find(Auth::user()->id);
        //     $user->type = "seller";
        //     $user->save();
        // });
        // return view('merchant.index', ["shop" => $newShop]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show($shop)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit($shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShopRequest  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $shop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy($shop)
    {
        //
    }

    public function showChat()
    {
        $shop = Shop::where("user_id", Auth::user()->id)->first();
        $allCustomerChats = Chat::with(["contents", "user"])
            ->where("shop_id", $shop->id)->get();
        return view('merchant.chat', compact("shop", "allCustomerChats"));
    }
    public function getSeller(Request $request)
    {
        return response()->json(["data" => Shop::find($request->get("id"))]);
    }
}
