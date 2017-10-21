<?php namespace App\Modules\Admin\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Managers\PrivacyOption\PrivacyOptionRepositoryInterface;
use App\Modules\Managers\VideoLog\VideoLogRepositoryInterface;
use App\Modules\Managers\VideoHistroy\VideoHistoryRepositoryInterface;
use App\Modules\Video\Validators\VideoValidator;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use App\Modules\Video\Transformers\PrivacyOptionTransformer;
use Illuminate\Support\Facades\Storage;

class AdminVideoApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, VideoRepositoryInterface $videoRepo, PrivacyOptionRepositoryInterface $privacyOptionsRepo, VideoLogRepositoryInterface $videoLogRepo, VideoHistoryRepositoryInterface $videoHistoryRepo)
    {
        $this->video = $videoRepo;
        $this->videoHistory = $videoHistoryRepo;
        $this->videoValidator = new VideoValidator();
        $this->privacyOptions = $privacyOptionsRepo;
        $this->videoLog = $videoLogRepo;
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    
    public function index(Request $request)
    {
        $videos = $this->video->all();
        return $this->respondWithCollection($videos, new VideoTransformer());
    }
    
    public function show(Request $request, $id)
    {
        $video = $this->video->findWhere(['uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        return $this->respondWithItem($video, new VideoTransformer());
//        $video = $this->video->findWhere(['uuid' => $id, 'active' => 1, 'admin_active' => 1])->first();
//        if(!$video)
//        {
//            return $this->errorWrongArgs(['Video not found']);
//        }
//        return $this->respondWithItem($video, new VideoTransformer());
    }
    
    public function showMe(Request $request, $id)
    {
        $videos = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        return $this->respondWithItem($videos, new VideoTransformer());
    }


    public function update(Request $request, $id)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        
        $data = $request->get('data');
        $validation = $this->videoValidator->store($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$video)
        {
            return $this->respondWithError([['Video not found']], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $video = $this->video->updateData($data, $id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        $video = $this->video->deleteData($id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function activate(Request $request, $id)
    {
        $data = $request->get('data');
        $video = $this->video->findWhere(['uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        $data['active'] = 1;
        $video = $this->video->updateData($data, $id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function deactivate(Request $request, $id)
    {
        $data = $request->get('data');
        $video = $this->video->findWhere(['uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        $data['active'] = 0;
        $video = $this->video->updateData($data, $id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function privacyOptions()
    {
        $privacyOptions = $this->privacyOptions->all();
        return $this->respondWithCollection($privacyOptions, new PrivacyOptionTransformer());
    }
    
    public function searchVideos($name, $noOfResults)
    {
        $videos = $this->video->searchVideos($name, $noOfResults);
        return $this->respondWithCollection($videos, new VideoTransformer());
    }
    
    
    public function currentVideoPosition(Request $request, $id)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        $data = $request->get('data');
        
        $video = $this->video->findWhere(['uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        
        $validation = $this->videoValidator->videoLogInsert($data, $id);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        $data['video_id'] = $id;
        
        $log = $this->videoLog->insertVideoDetails($data);
        return $this->respondWithBoolean($log, new VideoTransformer());
    }
    
    
}