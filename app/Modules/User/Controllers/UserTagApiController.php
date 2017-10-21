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
use App\Modules\Helper\Helper;

use App\Modules\Managers\UserTag\UserTagRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Modules\User\Validators\UserTagValidator;
use App\Modules\User\Transformers\UserTagTransformer;
use App\Modules\Managers\Tag\TagRepositoryInterface;

class UserTagApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, UserTagRepositoryInterface $userTagRepo, TagRepositoryInterface $tagRepo)
    {
        $this->userTag = $userTagRepo;
        $this->tagRepo = $tagRepo;
        $this->userTagValidator = new UserTagValidator();
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
        
        
        $validation = $this->userTagValidator->store($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $tag = $this->tagRepo->findWhere(['uuid' => $data['tag_id']])->first();
        if(!$tag)
        {
            return $this->errorWrongArgs(array('Tag Not Found'));
        }
       
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        
        $user = $this->userTag->insertData($data);
       
        return $this->respondWithBoolean($user, new UserTagTransformer());
    }
    
    public function addCustom(Request $request)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $data = $request->get('data');
        
        $validation = $this->userTagValidator->storeCustom($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
       
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        $user = $this->userTag->insertCustomTag($data);
       
        return $this->respondWithBoolean($user, new UserTagTransformer());
    }
    
    public function index(Request $request)
    {
        $user = $this->userTag->findWhere(['user_id' => $request->get('id')]);
        if(!$user)
        {
            return $this->errorNotFound([['User not found']]);
        }
        return $this->respondWithCollection($user, new UserTagTransformer());
    }
    
    public function destroy(Request $request, $tagID)
    {
        //$userTag = $this->userTag->where('user_id', $request->get('id'))->where('tag_id', $tagID)->first();
        $userTag = $this->userTag->findWhere(['user_id' => $request->get('id'), 'tag_id' => $tagID])->first();
        
        if(!$userTag)
        {
            return $this->errorInternalError();
        }
       
        $res = $this->userTag->deleteData($tagID);
        return $this->respondWithBoolean($res, new UserTagTransformer());
    }
    
}


