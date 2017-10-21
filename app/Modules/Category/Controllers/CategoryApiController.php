<?php namespace App\Modules\Category\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Modules\Managers\Category\CategoryRepositoryInterface;
use App\Modules\Category\Transformers\CategoryTransformer;

use App\Modules\Helper\Helper;

class CategoryApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, CategoryRepositoryInterface $categoryRepo)
    {
        $this->category = $categoryRepo;
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function index(Request $request)
    {
        $categories = $this->category->all();
        return $this->respondWithCollection($categories, new CategoryTransformer());
    }
    
    public function show(Request $request, $id)
    {
        $channel = $this->category->findWhere(['uuid' => $id])->first();
        if(!$channel)
        {
            return $this->errorWrongArgs(['Category not found']);
        }
        return $this->respondWithItem($channel, new CategoryTransformer());
    }
}

