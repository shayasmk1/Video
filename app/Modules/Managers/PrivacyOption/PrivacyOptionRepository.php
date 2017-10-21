<?php namespace App\Modules\Managers\PrivacyOption;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\PrivacyOption\PrivacyOptionRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;

class PrivacyOptionRepository extends BaseRepository implements PrivacyOptionRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\PrivacyOption\\PrivacyOptionModel";
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