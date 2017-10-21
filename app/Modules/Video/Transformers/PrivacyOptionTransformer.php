<?php namespace App\Modules\Video\Transformers;

use App\Modules\Managers\PrivacyOption\PrivacyOptionModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class PrivacyOptionTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(PrivacyOptionModel $privacy)
    {
        return[
            'uuid'          => $privacy->uuid,
            'name'          => $privacy->name,
            'description'   => $privacy->description
        ];
    }
    
}
