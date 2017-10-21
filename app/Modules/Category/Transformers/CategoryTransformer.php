<?php namespace App\Modules\Category\Transformers;

use App\Modules\Managers\Category\CategoryModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class CategoryTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(CategoryModel $category)
    {
        return[
            'uuid'          => $category->uuid,
            'name'          => $category->name
        ];
    }
    
}
