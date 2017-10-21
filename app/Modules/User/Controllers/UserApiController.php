<?php namespace App\Modules\User\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Symfony\Component\DomCrawler\Form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Modules\Managers\User\UserRepositoryInterface;
use App\Modules\User\Validators\UserValidator;
use App\Modules\Helper\Helper;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Support\Facades\Mail;

class UserApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, UserRepositoryInterface $userRepo)
    {
        $this->user = $userRepo;
        $this->userValidator = new UserValidator();
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function store(Request $request)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $data = $request->get('data');
       
        $validation = $this->userValidator->store($data);
       
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['confirmation_code'] = md5(rand(10000000,99999999) . date('Ymhisd') . rand(10000000,99999999));
        $data['reconfirm_code'] = md5(rand(10000000,99999999) . date('dmYsih') . rand(10000000,99999999));
        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = $this->helper->addUuid();
        $user = $this->user->insertData($data);

        
        Mail::send('User::emails.activate',['user' => $data, 'url' => url('/'), 'UUID' => $data['uuid'], 'confirmation_code' => $data['confirmation_code'], 'reconfirm_code' => $data['reconfirm_code']],  function ($m) use($data) {
            $m->from('test@clusterinfos.com', 'Video');
            $m->to($data['email'], $data['first_name'] . ' ' . $data['last_name'])->subject('Confirm your account!');
        });
       
        return $this->respondWithBoolean($user, new UserTransformer());
    }
    
    public function index(Request $request)
    {
        $user = $this->user->getAllUsers();
        if(!$user)
        {
            return $this->errorWrongArgs(['User not found']);
        }
        return $this->respondWithCollection($user, new UserTransformer());
    }
    
    public function show(Request $request, $id)
    {
        //$user = $this->user->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if($request->exists('id'))
        {
            $user = $this->user->findWhere(['uuid' => $id])->first();
            if($user && ($user['uuid'] != $request->get('id')))
            {
                $user = $this->user->findWhere(['uuid' => $id, 'active' => 1])->first();
            }
            else if($user) {
                $user = $this->user->findWhere(['uuid' => $id])->first();
            }
        }
        else
        {
            $user = $this->user->findWhere(['uuid' => $id, 'active' => 1])->first();
        }
        if(!$user)
        {
            return $this->errorNotFound([['User not found']]);
        }
        return $this->respondWithItem($user, new UserTransformer());
    }
    
    public function me(Request $request)
    {
        
        $user = $this->user->findWhere(['uuid' => $request->get('id')])->first();
        if(!$user)
        {
            return $this->errorNotFound([['User not found']]);
        }
        return $this->respondWithItem($user, new UserTransformer());
    }

    public function update(Request $request)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        $data = $request->get('data');
        $validation = $this->userValidator->update($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $user = $this->user->findWhere(['uuid'  => $request->get('id')])->first();
        if(!$user)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $channel = $this->user->updateData($data, $request->get('id'));
        
        return $this->respondWithBoolean($channel, new UserTransformer());
    }
    
    public function destroy(Request $request)
    {
        $data = $request->get('data');
        $user = $this->user->findWhere(['uuid'  => $request->get('id')])->first();
        if(!$user)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        
        $res = $this->user->deleteData($request->get('id'));
        return $this->respondWithBoolean($res, new UserTransformer());
    }
    
    public function activate(Request $request)
    {
        $data = $request->get('data');
        $channel = $this->user->findWhere(['uuid'  => $request->get('id')])->first();
        if(!$channel)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        $data['active'] = 1;
        
        $res = $this->user->updateData($data, $request->get('id'));
        return $this->respondWithBoolean($res, new UserTransformer());
    }
    
    public function deactivate(Request $request)
    {
        $data = $request->get('data');
        $user = $this->user->findWhere(['uuid'  => $request->get('id')])->first();
        if(!$user)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        $data['active'] = 0;
        
        $res = $this->user->updateData($data, $request->get('id'));
        return $this->respondWithBoolean($res, new UserTransformer());
    }
    
    public function searchUsers($name, $noOfResults)
    {
        $users = $this->user->searchUsers($name, $noOfResults);
        return $this->respondWithCollection($users, new UserTransformer());
    }
}


