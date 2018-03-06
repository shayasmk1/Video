<?php namespace App\Modules\Managers\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'users';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','first_name','last_name','address', 'phone', 'email', 'password', 'registration_type', 'registration_reference_id', 'confirmation_code', 'reconfirm_code', 'active', 'google_app_name', 'google_client_id', 'google_client_secret', 'google_api_key', 'google_folder_id', 'dropbox_key', 'dropbox_secret', 'dropbox_access_token', 'color'];
    
    public function user_tags()
    {
        return $this->hasMany('App\Modules\Managers\UserTag\UserTagModel', 'user_id', 'uuid');
    }
}