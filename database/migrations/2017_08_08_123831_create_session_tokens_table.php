<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessionTokens', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('user_id');
            $table->string('client_id', 255);
            $table->string('token', 191)->unique();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('uuid');
            $table->foreign('user_id')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_tokens');
    }
}
