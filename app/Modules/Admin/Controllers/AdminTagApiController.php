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

//use App\Modules\Managers\Channel\ChannelRepository;
use App\Modules\Managers\Tag\TagRepositoryInterface;
use App\Modules\Admin\Validators\AdminTagValidator;
use App\Modules\Helper\Helper;
use App\Modules\Admin\Transformers\AdminTagTransformer;
use Illuminate\Support\Facades\Mail;


class AdminTagApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, TagRepositoryInterface $tagRepo)
    {
        $this->tag = $tagRepo;
        $this->adminTagValidator = new AdminTagValidator();
        
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
        $validation = $this->adminTagValidator->store($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        
        $tag = $this->tag->insertData($data);
        return $this->respondWithItem($tag, new AdminTagTransformer());
    }
    
    public function index(Request $request)
    {
        $tags = $this->tag->all();
        return $this->respondWithCollection($tags, new AdminTagTransformer());
    }
    
    public function show(Request $request, $id)
    {
        $tag = $this->tag->findWhere(['uuid' => $id])->first();
        if(!$tag)
        {
            return $this->errorWrongArgs(['Tag not found']);
        }
        return $this->respondWithItem($tag, new AdminTagTransformer());
    }


    public function update(Request $request, $id)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        
        $data = $request->get('data');
        $validation = $this->adminTagValidator->update($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $tag = $this->tag->findWhere(['uuid' => $id])->first();
        if(!$tag)
        {
            return $this->respondWithError(['Tag not found'], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $tag = $this->tag->updateData($data, $id);
        
        return $this->respondWithBoolean($tag, new AdminTagTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $tag = $this->tag->findWhere(['uuid' => $id])->first();
        if(!$tag)
        {
            return $this->errorWrongArgs(['Tag not found']);
        }
        $tag = $this->tag->deleteData($id);
        
        return $this->respondWithBoolean($tag, new AdminTagTransformer());
    }
    
    public function activate(Request $request, $id)
    {
        $data = $request->get('data');
        $tag = $this->tag->findWhere(['uuid' => $id])->first();
        if(!$tag)
        {
            return $this->errorWrongArgs(['Tag not found']);
        }
        $data['active'] = 1;
        $tag = $this->tag->updateData($data, $id);
        
        return $this->respondWithBoolean($tag, new AdminTagTransformer());
    }
    
    public function deactivate(Request $request, $id)
    {
        $data = $request->get('data');
        $tag = $this->tag->findWhere(['uuid' => $id])->first();
        if(!$tag)
        {
            return $this->errorWrongArgs(['Tag not found']);
        }
        $data['active'] = 0;
        $tag = $this->channel->updateData($data, $id);
        
        return $this->respondWithBoolean($tag, new AdminTagTransformer());
    }
}