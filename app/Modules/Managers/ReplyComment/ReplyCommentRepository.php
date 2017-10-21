<?php namespace App\Modules\Managers\ReplyComment;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\ReplyComment\ReplyCommentRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;

class ReplyCommentRepository extends BaseRepository implements ReplyCommentRepositoryInterface
{
//    public function __construct()
//    {
//        //parent::__construct($model);
//        $this->helper = new Helper();
//       // $this->model = $model;
//        
//    }
    
    function model()
    {
        return "App\\Modules\\Managers\\ReplyComment\\ReplyCommentModel";
    }
    
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