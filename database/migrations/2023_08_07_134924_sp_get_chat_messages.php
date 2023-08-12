<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpGetChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure="create procedure sp_get_chat_messages(in p_user_id int,in p_for_user_id int)
        begin
            declare v_user_messages json;
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    'id',um.id,
                    'message', um.message ,
                    'type_message', um.`type`,
                    'created_by',um.created_by,
                    'for',um.`for`,
                    'seen_at',um.seen_at,
                    'was_replied',um.was_replied
                    )
            )into v_user_messages
            FROM user_messages um
            WHERE (um.created_by = p_user_id  and um.for=p_for_user_id) or (um.for =p_user_id  and um.created_by=p_for_user_id);
            SELECT v_user_messages AS user_messages;
        end";
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_chat_messages");    
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
