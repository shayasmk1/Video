<?php namespace App\Modules\Video\Transformers;

use App\Modules\Managers\VideoHistory\VideoHistoryModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\Managers\User\UserModel;
use App\Modules\Video\Transformers\VideoTransformer;

class VideoHistoryTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = ['video'];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(VideoHistoryModel $history)
    {
        return[
            'video_id'          => $history->video_id,
            'user_id'          => $history->user_id,
            'created_at'        => date("d M Y", strtotime($history->created_at))
        ];
    }
    
    public function includeVideo(VideoHistoryModel $history)
    {
        return $this->item($history->video, new VideoTransformer());
    }
    
}
