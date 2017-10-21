<?php namespace App\Modules\Video\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Like\LikeRepositoryInterface;
use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use Illuminate\Support\Facades\Storage;

class LikeApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, LikeRepositoryInterface $likeRepo, VideoRepositoryInterface $videoRepo)
    {
        $this->like = $likeRepo;
        $this->video = $videoRepo;
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function like(Request $request, $videoID)
    {
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $videoID, 'active' => 1, 'admin_active' => 1])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        
        $video = $this->like->likeVideo($videoID, $request->get('id'));
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function dislike(Request $request, $videoID)
    {
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $videoID, 'active' => 1, 'admin_active' => 1])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        
        $video = $this->like->dislikeVideo($videoID, $request->get('id'));
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
}