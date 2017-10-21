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
use App\Modules\Managers\Channel\ChannelRepositoryInterface;
use App\Modules\Channel\Validators\ChannelValidator;
use App\Modules\Helper\Helper;
use App\Modules\Channel\Transformers\ChannelTransformer;
use App\Modules\Channel\Transformers\ChannelLogTransformer;
use Illuminate\Support\Facades\Mail;
use App\Modules\Managers\ChannelLog\ChannelLogRepositoryInterface;

class AdminChannelApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, ChannelRepositoryInterface $channelRepo, ChannelLogRepositoryInterface $channelLogRepo)
    {
        $this->channel = $channelRepo;
        $this->channelLogRepo = $channelLogRepo;
        $this->channelValidator = new ChannelValidator();
        
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
        $validation = $this->channelValidator->store($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        
        $channel = $this->channel->insertData($data);
        return $this->respondWithItem($channel, new ChannelTransformer());
    }
    
    public function index(Request $request)
    {
        $channels = $this->channel->paginate(10);
        return $this->respondWithCollection($channels, new ChannelTransformer());
    }
    
    public function show(Request $request, $id)
    {
        if($request->exists('id'))
        {
            $channel = $this->channel->findWhere(['uuid' => $id])->first();
            
            if($channel && ($channel['user_id'] != $request->get('id')))
            {
                return $this->respondWithItem([], new ChannelTransformer());
            }
        }
        else
        {
            $channel = $this->channel->findWhere(['uuid' => $id, 'active' => 1])->first();
        }
        if(!$channel)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        return $this->respondWithItem($channel, new ChannelTransformer());
    }


    public function update(Request $request, $id)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        
        $data = $request->get('data');
        $validation = $this->channelValidator->update($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $channel = $this->channel->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$channel)
        {
            return $this->respondWithError([['Channel not found']], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $channel = $this->channel->updateData($data, $id);
        
        return $this->respondWithBoolean($channel, new ChannelTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $channel = $this->channel->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$channel)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        $channel = $this->channel->deleteData($id);
        
        return $this->respondWithBoolean($channel, new ChannelTransformer());
    }
    
    public function activate(Request $request, $id)
    {
        $data = $request->get('data');
        $channel = $this->channel->findWhere(['uuid' => $id])->first();
        if(!$channel)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        $data['active'] = 1;
        $channel = $this->channel->updateData($data, $id);
        
        return $this->respondWithBoolean($channel, new ChannelTransformer());
    }
    
    public function deactivate(Request $request, $id)
    {
        $data = $request->get('data');
        $channel = $this->channel->findWhere(['uuid' => $id])->first();
        if(!$channel)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        $data['active'] = 0;
        $channel = $this->channel->updateData($data, $id);
        
        return $this->respondWithBoolean($channel, new ChannelTransformer());
    }
    
    public function searchChannels($name, $noOfResults)
    {
        $channels = $this->channel->searchChannels($name, $noOfResults);
        return $this->respondWithCollection($channels, new ChannelTransformer());
    }
}

