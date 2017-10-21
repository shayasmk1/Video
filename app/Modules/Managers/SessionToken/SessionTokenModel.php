<?php namespace App\Modules\Managers\SessionToken;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionTokenModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    protected $table = 'sessionTokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','user_id','client_id','token', 'expiry_date', 'device', 'os', 'os_version', 'ip', 'current_location'];
    
    public function user()
    {
         return $this->belongsTo('App\Modules\Managers\User\UserModel', 'user_id', 'uuid');

    }
}