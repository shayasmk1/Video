<?php namespace App\Modules\Admin\Transformers;

use App\Modules\Managers\Tag\TagModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class AdminTagTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(TagModel $tag)
    {
        return[
            'uuid'          => $tag->uuid,
            'name'          => $tag->name,
            'description'   => $tag->description,
            'created_at'    => date("d-m-Y H:i:s", strtotime($tag->created_at)),
            'updated_at'    => date("d-m-Y H:i:s", strtotime($tag->updated_at)),
            'active'        => $tag->active,
            'user_id'       => $tag->user_id
        ];
    }
    
}
