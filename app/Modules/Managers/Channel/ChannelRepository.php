<?php namespace App\Modules\Managers\Channel;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Channel\ChannelRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\Channel\ChannelModel;

class ChannelRepository extends BaseRepository implements ChannelRepositoryInterface
{
   
    
    function model()
    {
        return "App\\Modules\\Managers\\Channel\\ChannelModel";
    }
//    
//    public function __construct()
//    {
//        $this->channelModel = new ChannelModel();
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
    
    public function getAllChannels()
    {
        $this->channelModel = new ChannelModel();
        return $this->channelModel->where('active', 1)->paginate(10);
    }
    
    public function getMyChannels($userID)
    {
        $this->channelModel = new ChannelModel();
        return $this->channelModel->where('user_id', $userID)->paginate(10);
    }
    
    public function searchChannels($name, $noOfResults)
    {
        $this->model = new ChannelModel();
        return $this->model->where('name', 'LIKE', $name . '%')
                ->paginate($noOfResults);
    }
}