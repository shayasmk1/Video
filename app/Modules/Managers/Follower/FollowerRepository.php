<?php namespace App\Modules\Managers\Follower;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Follower\FollowerRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;

class FollowerRepository extends BaseRepository implements FollowerRepositoryInterface
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
        return "App\\Modules\\Managers\\Follower\\FollowerModel";
    }
    
    
    
    public function followUser($userByID, $userToID)
    {
        $this->helper = new Helper();
        $subscribed = $this->findWhere(['channel_id' => $channelID, 'user_id' => $userID])->first();
        
        if($subscribed && $subscribed->subscribed == 1)
        {
           return 1;
           exit;
        }
        
        if(!$subscribed)
        {
            $data['channel_id'] = $channelID;
            $data['user_id'] = $userID;
            $data['uuid'] = $this->helper->addUuid();
            $data['subscribed'] = 1;
            return $this->create($data);
            exit;
        }
        else
        {
            $data['subscribed'] = 1;
            return $this->update($data, $subscribed->uuid);
            exit;
        }
        
    }
    
    public function unfollowUser($userByID, $userToID)
    {
        $subscribed = $this->findWhere(['channel_id' => $channelID, 'user_id' => $userID])->first();
        if($subscribed && $subscribed->subscribed == 0)
        {
           return 1;
           exit;
        }
        if(!$subscribed)
        {
            $data['channel_id'] = $channelID;
            $data['user_id'] = $userID;
            $data['uuid'] = $this->helper->addUuid();
            $data['subscribed'] = 0;
            return $this->create($data);
            exit;
        }
        else
        {
            $data['subscribed'] = 0;
            return $this->update($data, $subscribed->uuid);
            exit;
        }
        
    }
    
}