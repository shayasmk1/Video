<?php namespace App\Modules\Managers\Video;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\Video\VideoModel;
use Illuminate\Support\Facades\DB;
use App\Modules\Managers\VideoTag\VideoTagRepositoryInterface;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    public function __contruct(VideoTagRepositoryInterface $videoTagRepo)
    {
        $this->video = $videoTagRepo;
    }
    
    function model()
    {
        return "App\\Modules\\Managers\\Video\\VideoModel";
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
            return $data['uuid'];
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
    
    public function searchVideos($name, $noOfResults)
    {
        $this->model = new VideoModel();
        return $this->model
                ->join('video_tags', 'video_tags.video_id', '=', 'videos.uuid')
                ->join('tags', 'tags.uuid', '=', 'video_tags.tag_id')
                ->where('videos.name', 'LIKE', $name . '%')
                ->orWhere('tags.name', '=', $name)->paginate($noOfResults);
    }
    
    public function getAllPublicVideos()
    {
        $this->model = new VideoModel();
        return $this->model->where('active',1)->where('admin_active',1)->paginate(10);
    }
}