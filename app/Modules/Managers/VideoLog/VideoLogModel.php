<?php namespace App\Modules\Managers\VideoLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoLogModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'video_logs';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','video_id','video_time','user_id','ip'];
    
    public function channel()
    {
        return $this->belongsTo('App\Modules\Managers\Channel\ChannelModel', 'channel_id', 'uuid');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Modules\Managers\User\UserModel', 'user_id', 'uuid');
    }
    
    public function privacyOption()
    {
        return $this->belongsTo('App\Modules\Managers\PrivacyOption\PrivacyOptionModel', 'privacy_option_id', 'uuid');
    }
    
}