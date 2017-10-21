<?php namespace App\Modules\Managers\VideoLog;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\VideoLog\VideoLogRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class VideoLogRepository extends BaseRepository implements VideoLogRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\VideoLog\\VideoLogModel";
    }
//    

    public function insertVideoDetails($data)
    {
        $video = $this->findWhere(['user_id' => $data['user_id'], 'video_id' => $data['video_id']])->first();
        if(!$video)
        {
            return $this->insertData($data);
            exit;
        }
        
        return $this->updateData($data, $video->uuid);
        exit;
    }
    
    private function insertData($data)
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
            return 1;
        }
        catch(ValidationException $e)
        {
            DB::rollback();
            return 0;
        }
    }
    
    private function updateData($data, $id)
    {
        return $this->update($data, $id);
    }
    
}