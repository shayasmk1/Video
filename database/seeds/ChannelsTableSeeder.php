<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;
use App\Modules\Managers\User\UserManager;
use App\Modules\Managers\PrivacyOption\PrivacyOptionModel;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->privacyOption = new PrivacyOptionModel();
        $this->user = new UserManager();
        $privacyOption = $this->privacyOption->where('name', 'Public')->first();
        $user = $this->user->where('email', 'admin@admin.com')->first();
        
        DB::table('channels')->delete();
        DB::table('channels')->insert([
            'name'              => 'General',
            'privacy_option_id' => $privacyOption->uuid,
            'UUID'              => Uuid::generate()->string,
            'user_id'           => $user->uuid,
            'active'            => 1
        ]);
        
    }
}
