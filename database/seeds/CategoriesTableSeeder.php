<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name'              => 'Vine',
            'UUID'              => Uuid::generate()->string
        ]);
        
        DB::table('categories')->insert([
            'name'              => 'Podcast',
            'UUID'              => Uuid::generate()->string
        ]);
        
        DB::table('categories')->insert([
            'name'              => 'Series',
            'UUID'              => Uuid::generate()->string
        ]);
        
        DB::table('categories')->insert([
            'name'              => 'General',
            'UUID'              => Uuid::generate()->string
        ]);
        
    }
}
