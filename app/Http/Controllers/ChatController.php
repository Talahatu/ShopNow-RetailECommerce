<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatContent;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function loadChats(Request $request)
    {
        $chat = Chat::with(["contents"])
            ->where("shop_id", $request->get("sellerID"))
            ->where("user_id", $request->get("userID"))
            ->first();
        return response()->json($chat->contents);
    }
    public function showChat($id)
    {
        $chat = Chat::firstOrCreate([
            "user_id" => Auth::user()->id,
            "shop_id" => $id
        ]);
        $chats = Chat::with(["contents", "shop"])
            ->where("user_id", Auth::user()->id)
            ->get();

        return view("regular.chat", compact("chats"));
    }
    public function showAllChat()
    {
        $chats = Chat::with(["contents", "shop"])
            ->where("user_id", Auth::user()->id)
            ->get();
        return view('regular.chat', compact("chats"));
    }
    public function sendMessage(Request $request)
    {
        $message = $request->get("content");
        $sellerID = $request->get("sellerID");
        $id = Auth::user()->id;

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

        $data['message'] = $message;
        $data["key"] = "regular";
        $data["time"] = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();



        // regular-seller
        $pusher->trigger('private-my-channel-' . $id . '-' . $sellerID, 'client-load-chats', $data);
        $chat = Chat::where("user_id", $id)->where("shop_id", $sellerID)->first();
        $newChat = new ChatContent();
        $newChat->chat_id = $chat->id;
        $newChat->date = $data["time"];
        $newChat->content = $message;
        $newChat->sender = "customer";
        $newChat->save();
        return response()->json(["data" => $data, "message" => $message]);
    }
    public function sendMessageSeller(Request $request)
    {
        $message = $request->get("content");
        $customerID = $request->get("customerID");
        $shopID = $request->get("shopID");

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

        $data['message'] = $message;
        $data["key"] = "seller";
        $data["time"] = Carbon::now(new DateTimeZone("Asia/Jakarta"))->toDateTimeString();

        // regular-seller
        $pusher->trigger('private-my-channel-' . $customerID . '-' . $shopID, 'client-load-chats', $data);
        $chat = Chat::where("user_id", $customerID)->where("shop_id", $shopID)->first();
        $newChat = new ChatContent();
        $newChat->chat_id = $chat->id;
        $newChat->date = $data["time"];
        $newChat->content = $message;
        $newChat->sender = "seller";
        $newChat->save();
        return response()->json(["data" => $data, "message" => $message]);
    }
}
