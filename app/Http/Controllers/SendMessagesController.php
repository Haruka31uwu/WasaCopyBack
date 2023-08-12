<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\SendMessageTextEvent;
class SendMessagesController extends Controller
{
    public function sendMessageText(Request $request){
        DB::table('user_messages')->insert($request->all());
        $lastId = DB::getPdo()->lastInsertId();
        $messageData = DB::table('user_messages')->where('id',$lastId)->first();
        event(new SendMessageTextEvent($messageData));
    }
}
