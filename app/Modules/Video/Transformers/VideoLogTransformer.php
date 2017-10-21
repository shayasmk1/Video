<?php namespace App\Modules\Video\Transformers;

use App\Modules\Managers\VideoLog\VideoLogModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\Video\Transformers\ReplyCommentTransformer;
use App\Modules\User\Transformers\UserTransformer;

class VideoLogTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(VideoLogModel $log)
    {
        return[
            'video_time'      => $log->video_time,
            'ip'       =>  $log->ip,
            'updated_at'       => date("d M Y H:i:s", strtotime($log->updated_at))
        ];
    }
    
}
