<?php namespace App\Modules\Channel\Transformers;

use App\Modules\Managers\ChannelLog\ChannelLogModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\Video\Transformers\ReplyCommentTransformer;
use App\Modules\User\Transformers\UserTransformer;

class ChannelLogTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(ChannelLogModel $log)
    {
        return[
            'video_time'        => $log->video_time,
            'ip'                =>  $log->ip,
            'updated_at'        => date("d M Y H:i:s", strtotime($log->updated_at))
        ];
    }
    
}
