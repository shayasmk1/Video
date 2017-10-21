<?php namespace App\Modules\Managers\UserCategory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Database\Eloquent;

class CategoryModel extends Model
{
    use SoftDeletes;
 //   use Authenticatable, CanResetPassword;

    /**
     * @var string
     */
    public $incrementing = false;
    protected $table = 'categories';
    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = [];
    
    
}