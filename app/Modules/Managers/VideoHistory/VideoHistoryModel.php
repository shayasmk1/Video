<?php namespace App\Modules\Managers\VideoHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoHistoryModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'video_history';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','video_id','user_id'];
    
    public function video()
    {
        return $this->belongsTo('App\Modules\Managers\Video\VideoModel', 'video_id', 'uuid');
    }
    
}