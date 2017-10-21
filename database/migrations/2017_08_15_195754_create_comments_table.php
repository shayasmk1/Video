<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('video_id');
            $table->text('comment');
             $table->uuid('user_id');
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('uuid');
            $table->foreign('user_id')->references('uuid')->on('users');
            $table->foreign('video_id')->references('uuid')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
