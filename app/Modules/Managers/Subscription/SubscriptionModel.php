<?php namespace App\Modules\Managers\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'subscriptions';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','channel_id','user_id', 'subscribed'];
    
    
}