<?php namespace App\Modules\Managers\Subscription;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Subscription\SubscriptionRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
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
        return "App\\Modules\\Managers\\Subscription\\SubscriptionModel";
    }
    
    
    
    public function subscribeChannel($channelID, $userID)
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
    
    public function unsubscribeChannel($channelID, $userID)
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