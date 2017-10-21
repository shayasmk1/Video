<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSessionTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessionTokens', function (Blueprint $table) {
            $table->string('device');
            $table->string('os');
            $table->string('os_version')->nullable();
            $table->string('ip')->nullable();
            $table->string('current_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessionTokens', function (Blueprint $table) {
            //
        });
    }
}
