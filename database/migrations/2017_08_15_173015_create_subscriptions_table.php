<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('channel_id');
            $table->uuid('user_id');
            $table->boolean('subscribed')->default(1);
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('uuid');
            $table->foreign('user_id')->references('uuid')->on('users');
            $table->foreign('channel_id')->references('uuid')->on('channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
