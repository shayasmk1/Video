<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('last_name')->after('name');
            $table->text('address')->nullable()->after('last_name');
            $table->string('phone')->nullable()->after('address');
            $table->string('registration_type')->nullable()->after('phone')->default('general');
            $table->string('registration_reference_id')->nullable()->after('registration_type');
            $table->renameColumn('name','first_name');
            $table->softDeletes();
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
