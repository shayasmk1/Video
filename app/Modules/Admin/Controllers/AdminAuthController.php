<?php namespace App\Modules\Admin\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;

use App\Modules\Managers\User\UserRepositoryInterface;
use App\Modules\User\Validators\UserValidator;
use App\Modules\Helper\Helper;
use App\Modules\Auth\Transformers\LoginTransformer;

use App\Modules\Managers\SessionToken\SessionTokenRepositoryInterface;
use App\Modules\Auth\Validators\LoginValidator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AdminAuthController extends ApiBaseController
{
    public function __construct(Manager $fractal, UserRepositoryInterface $userRepo, SessionTokenRepositoryInterface $tokenRepo)
    {
        $this->user = $userRepo;
        $this->token = $tokenRepo;
        $this->userValidator = new UserValidator();
        $this->helper = new Helper();
        $this->loginValidator = new LoginValidator();
        parent::__construct($fractal);
        
    }
    
    public function login(Request $request)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $data = $request->get('data');
        $validation = $this->loginValidator->login($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        $data = $this->helper->clearEmptyValues($data);
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'registration_type' => 'admin', 'active' => 1], 0)) {
            $user = $this->user->getCurrentUser($data['email']);
            unset($data['email']);
            unset($data['password']);
            $sessionData = $data;
            $sessionData['uuid'] = $this->helper->addUuid();
            $sessionData['token'] = $this->helper->addUuid() . '-' . $this->helper->addUuid();
            $sessionData['expiry_date'] = null;
            $sessionData['user_id'] = $user->uuid;
            
            $token = $this->token->insertData($sessionData);
            return $this->respondWithItem($token, new LoginTransformer());
        }
        
        return $this->errorUnauthorized(['Email and Password does not match']);
    }
}


