<?php namespace App\Modules\Managers\UserTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTagModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'user_tags';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','tag_id','user_id'];
    
    public function tag()
    {
        return $this->belongsTo('App\Modules\Managers\Tag\TagModel', 'tag_id', 'uuid');
    }
    
}