<?php namespace App\Modules\Auth\Transformers;

use App\Modules\Managers\SessionToken\SessionTokenModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class LoginTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(SessionTokenModel $token)
    {
        return[
            'token' => $token->token,
            'client' => $token->client_id,
            'device' => $token->device,
            'os' => $token->os
        ];
    }
    
}
