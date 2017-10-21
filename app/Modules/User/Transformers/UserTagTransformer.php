<?php namespace App\Modules\User\Transformers;

use App\Modules\Managers\User\UserModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\Managers\UserTag\UserTagModel;
use App\Modules\Tag\Transformers\TagTransformer;

class UserTagTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = ['tag'];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(UserTagModel $userTag)
    {
        return[
            'tag_id' => $userTag->tag_id];
    }
    
    public function includeTag(UserTagModel $userTag)
    {
        return $this->item($userTag->tag, new TagTransformer());
    }
    
}
