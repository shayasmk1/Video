<?php namespace App\Modules\Admin\Controllers;

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

class AdminUserApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, UserRepositoryInterface $userRepo)
    {
        $this->user = $userRepo;
        $this->userValidator = new UserValidator();
        $this->helper = new Helper();
        parent::__construct($fractal);
    }

    public function update(Request $request, $id)
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
        
        $user = $this->user->findWhere(['uuid'  => $id])->first();
        if(!$user)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $channel = $this->user->updateData($data, $id);
        
        return $this->respondWithBoolean($channel, new UserTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $user = $this->user->findWhere(['uuid'  => $id])->first();
        if(!$user)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        
        $res = $this->user->deleteData($id);
        return $this->respondWithBoolean($res, new UserTransformer());
    }
    
    public function activate(Request $request, $id)
    {
        $data = $request->get('data');
        $channel = $this->user->findWhere(['uuid'  => $id])->first();
        if(!$channel)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        $data['active'] = 1;
        
        $res = $this->user->updateData($data, $id);
        return $this->respondWithBoolean($res, new UserTransformer());
    }
    
    public function deactivate(Request $request, $id)
    {
        $data = $request->get('data');
        $user = $this->user->findWhere(['uuid'  => $id])->first();
        if(!$user)
        {
            return $this->respondWithError([['User not found']], 404);
        }
        $data['active'] = 0;
        
        $res = $this->user->updateData($data, $id);
        return $this->respondWithBoolean($res, new UserTransformer());
    }
    
    public function index(Request $request)
    {
        $users = $this->user->all();
        if(empty($users))
        {
            return $this->errorWrongArgs(['Users not found']);
        }
        return $this->respondWithCollection($users, new UserTransformer());
    }
    
    public function show(Request $request, $id)
    {
        $user = $this->user->findWhere(['uuid' => $id])->first();
        if(!$user)
        {
            return $this->errorNotFound([['User not found']]);
        }
        return $this->respondWithItem($user, new UserTransformer());
    }
    
    public function searchUsers($name, $noOfResults)
    {
        $users = $this->user->searchUsers($name, $noOfResults);
        return $this->respondWithCollection($users, new UserTransformer());
    }
}


