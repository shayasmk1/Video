<?php namespace App\Modules\Channel\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Subscription\SubscriptionRepositoryInterface;
use App\Modules\Managers\Channel\ChannelRepositoryInterface;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use Illuminate\Support\Facades\Storage;

class SubscriptionApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, SubscriptionRepositoryInterface $subscriptionRepo, ChannelRepositoryInterface $channelRepo)
    {
        $this->subscription = $subscriptionRepo;
        $this->channel = $channelRepo;
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function subscribe(Request $request, $channelID)
    {
        $channel = $this->channel->findWhere(['user_id'  => $request->get('id'), 'uuid' => $channelID, 'active' => 1])->first();
        if(!$channel)
        {
            return $this->errorWrongArgs(['Channel not found']);
        }
        
        $channel = $this->subscription->subscribeChannel($channelID, $request->get('id'));
        return $this->respondWithBoolean($channel, new VideoTransformer());
    }
    
    public function unsubscribe(Request $request, $channelID)
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