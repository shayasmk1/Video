<?php namespace App\Modules\Managers\Channel;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Database\Eloquent;

class ChannelModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'channels';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','name','description','active', 'user_id', 'privacy_option_id'];
    
    
}