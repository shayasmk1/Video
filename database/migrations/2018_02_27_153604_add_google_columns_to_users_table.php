<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_app_name', 500)->nullable()->after('reconfirm_code');
            $table->string('google_client_id', 500)->nullable()->after('google_app_name');
            $table->string('google_client_secret', 500)->nullable()->after('google_client_id');
            $table->string('google_api_key', 500)->nullable()->after('google_client_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
