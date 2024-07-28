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
            env("PUSHER_APP_KEY", "c58a82be41ea6c60c1d7"),
            env("PUSHER_APP_SECRET", "8264fc21e2b5035cc329"),
            env("PUSHER_APP_ID", "1716744"),
            array('cluster' => env("PUSHER_APP_CLUSTER", "ap1"))
        );
        echo $pusher->authorizeChannel($request->channel_name, $request->socket_id);
    }
}
