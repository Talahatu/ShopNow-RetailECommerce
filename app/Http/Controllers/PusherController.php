<?php

namespace App\Http\Controllers;

use App\Events\Chat;
use Illuminate\Http\Request;
use Pusher\Pusher;

class PusherController extends Controller
{

    public function auth(Request $request)
    {
        $pusher = new Pusher(
            env("PUSHER_APP_KEY"),
            env("PUSHER_APP_SECRET"),
            env("PUSHER_APP_ID"),
            array('cluster' => env("PUSHER_APP_CLUSTER"))
        );
        echo $pusher->authorizeChannel($request->channel_name, $request->socket_id);
    }
}
