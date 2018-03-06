<?php namespace App\Modules\User\Transformers;

use App\Modules\Managers\User\UserModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Transformers\UserTagTransformer;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = ['userTag'];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(UserModel $user)
    {
        return[
            'uuid'    => $user->uuid,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'address'   => $user->address,
            'phone'     => $user->phone,
            'active'    => $user->active,
            'registration_type' => $user->registration_type,
            'color'     => $user->color
        ];
    }
    
    public function includeUserTag(UserModel $user)
    {
        return $this->collection($user->user_tags, new UserTagTransformer());
    }
    
}
