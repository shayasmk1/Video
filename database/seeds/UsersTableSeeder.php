<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name'              => 'Admin',
            'last_name'              => 'Admin',
            'UUID'              => Uuid::generate()->string,
            'email'             => 'admin@admin.com',
            'password'          => Hash::make('rr113Password'),
            'registration_type'              => 'admin',
            'active'            => 1
        ]);
    }
}
