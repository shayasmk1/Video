<?php namespace App\Modules\Managers\Comment;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Comment\CommentRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\Comment\CommentModel;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
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
        return "App\\Modules\\Managers\\Comment\\CommentModel";
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
    
    public function findAllCommentsOfVideo($videoID)
    {
        $this->model = new CommentModel();
        return $this->model->with('reply', 'user')->where('video_id', $videoID)->paginate(10);
    }
}