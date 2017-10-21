<?php namespace App\Modules\Managers\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class UserManager extends Model
{
    protected $table = 'users';
    
    public function getUserStatisticsOverTags($userID)
    {
        
    } 
}