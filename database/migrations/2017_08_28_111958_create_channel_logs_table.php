<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_logs', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('channel_id');
            $table->uuid('video_id');
            $table->time('video_time');
            $table->uuid('user_id');
            $table->uuid('ip')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('uuid');
            $table->foreign('channel_id')->references('uuid')->on('channels');
            $table->foreign('video_id')->references('uuid')->on('videos');
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
        Schema::dropIfExists('channel_logs');
    }
}
