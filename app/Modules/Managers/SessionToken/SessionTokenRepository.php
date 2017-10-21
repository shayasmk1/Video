<?php namespace App\Modules\Managers\SessionToken;

use App\Modules\AbstractRepository;
use App\Modules\Managers\SessionToken\SessionTokenRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Managers\SessionToken\SessionTokenModel;
use App\Modules\Helper\Helper;

class SessionTokenRepository extends AbstractRepository implements SessionTokenRepositoryInterface
{
    
    public function __construct(SessionTokenModel $model)
    {
        $this->helper = new Helper();
        $this->model = $model;
        //parent::__construct($model);
    }
    
    
    
    public function insertData($data)
    {
        return $this->create($data);
    }
}