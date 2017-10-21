<?php namespace App\Modules\Managers\Tag;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Tag\TagRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\Tag\\TagModel";
    }
//    
//    public function __construct(ChannelModel $model)
//    {
//        $this->helper = new Helper();
//        $this->model = $model;
//        //parent::__construct($model);
//    }
    
    public function insertData($data)
    {
        return $this->create($data);
        
    }
    
    public function updateData($data, $id)
    {
        return $this->update($data, $id);
    }
    
    public function deleteData($id)
    {
        return $this->deleteWhere(array('uuid' => $id));
    }
}