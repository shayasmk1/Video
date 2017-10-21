<?php namespace App\Modules\Managers\Category;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Category\CategoryRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\Category\\CategoryModel";
    }
}