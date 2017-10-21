<?php namespace App\Modules\Managers\PrivacyOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivacyOptionModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'privacy_options';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['uuid','name','description'];
    
    
}