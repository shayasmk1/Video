<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('followed_to_id');
            $table->uuid('followed_by_id');
            $table->boolean('followed')->default(1);
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('uuid');
            $table->foreign('followed_to_id')->references('uuid')->on('users');
            $table->foreign('followed_by_id')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
