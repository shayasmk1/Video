<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_history', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('video_id');
            $table->uuid('user_id');
            $table->timestamps();
            
            $table->softDeletes();
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
        Schema::dropIfExists('video_history');
    }
}
