<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class PrivacyOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('privacy_options')->insert([
            'name'              => 'Public',
            'description'       => 'This is publicily visible',
            'UUID'              => Uuid::generate()->string
        ]);
        
        DB::table('privacy_options')->insert([
            'name'              => 'Private',
            'description'       => 'This is only available to the persons who have access to the video',
            'UUID'              => Uuid::generate()->string
        ]);
        
        DB::table('privacy_options')->insert([
            'name'              => 'Unlisted',
            'description'       => 'This is a private video, but will not be listed in searches',
            'UUID'              => Uuid::generate()->string
        ]);
    }
}
