<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    //
    public function getSingleChatMessages(Request $request)
    {
        try {
            $params=[
                $request['user_id'],
                $request['for_user_id']
            ];
            $messages = DB::select("call sp_get_chat_messages(?,?)",$params);
            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
