<?php namespace App\Modules\Managers\VideoTag;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\Video\VideoModel;
use Illuminate\Support\Facades\DB;

class VideoTagRepository extends BaseRepository implements VideoRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\VideoTag\\VideoTagModel";
    }
//    

    
    public function insertData($data)
    {
        DB::beginTransaction();
        try 
        {
            $video =  $this->create($data);
            if(!$video)
            {
                return 0;
            }
            
            DB::commit();
            return $data['UUID'];
        }
        catch(ValidationException $e)
        {
            DB::rollback();
            return 0;
        }
    }
    
    public function updateData($data, $id)
    {
        return $this->update($data, $id);
    }
    
    public function deleteData($id)
    {
        return $this->deleteWhere(array('uuid' => $id));
    }
    
    public function searchVideos($name)
    {
        $this->model = new VideoModel();
        return $this->model->where('name', 'LIKE', $name . '%')->get();
    }
    
    public function getAllPublicVideos()
    {
        $this->model = new VideoModel();
        return $this->model->with('channel', 'user', 'privacyOption')->where('active',1)->where('admin_active',1)->paginate(10);
    }
}