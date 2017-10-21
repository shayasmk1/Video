<?php namespace App\Modules\Managers\Like;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Like\LikeRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\Like\LikeModel;

class LikeRepository extends BaseRepository implements LikeRepositoryInterface
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
        return "App\\Modules\\Managers\\Like\\LikeModel";
    }
    
    
    
    public function likeVideo($videoID, $userID)
    {
        $this->helper = new Helper();
        $like = $this->findWhere(['video_id' => $videoID, 'user_id' => $userID])->first();
        
        if($like && $like->like_boolean == 1)
        {
           return 1;
           exit;
        }
        
        if(!$like)
        {
            $data['video_id'] = $videoID;
            $data['user_id'] = $userID;
            $data['uuid'] = $this->helper->addUuid();
            $data['like_boolean'] = 1;
            return $this->create($data);
            exit;
        }
        else
        {
            $data['like_boolean'] = 1;
            return $this->update($data, $like->uuid);
            exit;
        }
        
    }
    
    public function dislikeVideo($videoID, $userID)
    {
        $like = $this->findWhere(['video_id' => $videoID, 'user_id' => $userID])->first();
        if($like && $like->like_boolean == 0)
        {
           return 1;
           exit;
        }
        if(!$like)
        {
            $data['video_id'] = $videoID;
            $data['user_id'] = $userID;
            $data['uuid'] = $this->helper->addUuid();
            $data['like_boolean'] = 0;
            return $this->create($data);
            exit;
        }
        else
        {
            $data['like_boolean'] = 0;
            return $this->update($data, $like->uuid);
            exit;
        }
        
    }
    
}