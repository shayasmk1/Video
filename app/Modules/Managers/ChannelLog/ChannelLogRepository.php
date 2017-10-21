<?php namespace App\Modules\Managers\ChannelLog;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\ChannelLog\ChannelLogRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\Channel\ChannelModel;

class ChannelLogRepository extends BaseRepository implements ChannelLogRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\ChannelLog\\ChannelLogModel";
    }
    
    public function insertChannelDetails($data)
    {
        $video = $this->findWhere(['user_id' => $data['user_id'], 'channel_id' => $data['channel_id']])->first();
        if(!$video)
        {
            return $this->insertData($data);
            exit;
        }
        
        return $this->updateData($data, $video->uuid);
        exit;
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
    
    public function getAllChannels()
    {
        $this->channelModel = new ChannelModel();
        return $this->channelModel->where('active', 1)->paginate(10);
    }
    
    public function getMyChannels($userID)
    {
        $this->channelModel = new ChannelModel();
        return $this->channelModel->where('user_id', $userID)->paginate(2);
    }
    
    public function searchChannels($name, $noOfResults)
    {
        $this->model = new ChannelModel();
        return $this->model->where('name', 'LIKE', $name . '%')
                ->paginate($noOfResults);
    }
}