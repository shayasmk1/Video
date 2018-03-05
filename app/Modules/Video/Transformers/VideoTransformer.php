<?php namespace App\Modules\Video\Transformers;

use App\Modules\Managers\Video\VideoModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\Managers\User\UserModel;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\Channel\Transformers\ChannelTransformer;
use App\Modules\Video\Transformers\PrivacyOptionTransformer;
use App\Modules\Video\Transformers\VideoLogTransformer;

class VideoTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = ['user', 'channel', 'privacyOption', 'videoLog'];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(VideoModel $video)
    {
        return[
            'uuid'          => $video->uuid,
            'name'          => $video->name,
            'description'   => $video->description,
            'type'          => $video->type,
            'url'           => $video->url,
            'created_at'    => date("d-m-Y H:i:s", strtotime($video->created_at)),
            'active'        => $video->active,
            'admin'         => $video->admin_active,
            'comment'       => $video->comment,
            'channel_id'    => $video->channel_id,
            'privacy_option_id'    => $video->privacy_option_id,
            'user_id'       => $video->user_id,
            'thumbnail'     => $video->thumbnail,
            'embed'         => $video->embed
        ];
    }
    
    public function includeUser(VideoModel $video)
    {
        return $this->item($video->user, new UserTransformer());
    }
    
    public function includeChannel(VideoModel $video)
    {
        return $this->item($video->channel, new ChannelTransformer());
    }
    
    public function includePrivacyOption(VideoModel $video)
    {
        return $this->item($video->privacyOption, new PrivacyOptionTransformer());
    }
    
    public function includeVideoLog(VideoModel $video)
    {
        if($video->videoLog != null)
        {
            return $this->item($video->videoLog, new VideoLogTransformer());
        }
    }
}
