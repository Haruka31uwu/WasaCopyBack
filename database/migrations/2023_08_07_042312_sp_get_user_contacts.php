<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpGetUserContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure= "
        CREATE PROCEDURE `sp_get_user_contacts`(
            IN `p_user_id` INT
        )
        BEGIN
        DECLARE v_contactsJson JSON;
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                'contact_id', uc.contact_id,
                'contact_name', uc.know_as
            )
        ) INTO v_contactsJson
        FROM user_contacts uc
        WHERE uc.user_id = p_user_id;
        SELECT v_contactsJson AS user_contacts_json;
        END
        ";
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_user_contacts");
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
