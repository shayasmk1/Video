<?php namespace App\Modules\Managers\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'comments';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','video_id','user_id', 'comment'];
    
    public function reply()
    {
        return $this->hasMany('App\Modules\Managers\ReplyComment\ReplyCommentModel', 'comment_id', 'uuid');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Modules\Managers\User\UserModel', 'user_id', 'uuid');
    }
}