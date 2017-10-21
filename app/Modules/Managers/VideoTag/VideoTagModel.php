<?php namespace App\Modules\Managers\VideoTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoTagModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'video_tags';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','name','description','active','admin_active', 'user_id', 'privacy_option_id', 'embed', 'thumbnail', 'type', 'url', 'channel_id'];
    
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