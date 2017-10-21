<?php namespace App\Modules\Channel\Transformers;

use App\Modules\Managers\Channel\ChannelModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class ChannelTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(ChannelModel $channel)
    {
        return[
            'uuid'            => $channel->uuid,
            'name'          => $channel->name,
            'description'   => $channel->description,
            'created_at'    => date("d-m-Y H:i:s", strtotime($channel->created_at)),
            'active'        => $channel->active
        ];
    }
    
}
