<?php namespace App\Modules\Tag\Transformers;

use App\Modules\Managers\Tag\TagModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class TagTransformer extends TransformerAbstract
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
            'name' => $tag->name,
            'description' => $tag->description,
            'active'   => $tag->active
        ];
    }
    
}
