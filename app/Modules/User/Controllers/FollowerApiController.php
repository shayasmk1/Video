<?php namespace App\Modules\User\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Follower\FollowerRepositoryInterface;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use Illuminate\Support\Facades\Storage;

class FollowerApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, FollowerRepositoryInterface $followRepo)
    {
        $this->follower = $followRepo;
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function follow(Request $request, $channelID)
    {
        $channel = $this->channel->findWhere(['user_id'  => $request->get('id'), 'uuid' => $channelID, 'active' => 1])->first();
        if(!$channel)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        
        $channel = $this->subscription->subscribeChannel($channelID, $request->get('id'));
        return $this->respondWithBoolean($channel, new VideoTransformer());
    }
    
    public function unfollow(Request $request, $channelID)
    {
        $video = $this->channel->findWhere(['user_id'  => $request->get('id'), 'uuid' => $channelID, 'active' => 1])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        
        $channel = $this->subscription->unsubscribeChannel($channelID, $request->get('id'));
        return $this->respondWithBoolean($channel, new VideoTransformer());
    }
}