<?php namespace App\Modules\Managers\Follower;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowerModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'followers';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','followed_to_id','followed_by_id', 'followed'];
    
    
}