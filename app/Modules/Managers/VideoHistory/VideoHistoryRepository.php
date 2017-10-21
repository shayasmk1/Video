<?php namespace App\Modules\Managers\VideoHistory;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\VideoHistory\VideoHistoryRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Modules\Managers\VideoHistory\VideoHistoryModel;

class VideoHistoryRepository extends BaseRepository implements VideoHistoryRepositoryInterface
{
    function model()
    {
        return "App\\Modules\\Managers\\VideoHistory\\VideoHistoryModel";
    }
    
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
            return 1;
        }
        catch(ValidationException $e)
        {
            DB::rollback();
            return 0;
        }
    }
    
    public function videoTraffic($videoID, $fromDate = null, $toDate = null)
    {
        $this->model = new VideoHistoryModel();
        return $this->model->select(DB::raw('COUNT(*) AS user_count'), 'videos.name AS video', DB::raw('DATE(video_history.created_at) AS date_date'))
                ->join('videos', 'videos.uuid', '=', 'video_history.video_id')
                ->where(function($query) use ($fromDate)
                {
                    if($fromDate != null)
                    {
                        $query->where(DB::raw('DATE(video_history.created_at)'), '>=', date("Y-m-d", strtotime($fromDate)));
                    }
                })
                ->where(function($query) use ($toDate)
                {
                    if($toDate != null)
                    {
                        $query->where(DB::raw('DATE(video_history.created_at)'), '<=', date("Y-m-d", strtotime($toDate)));
                    }
                })
                ->where('video_history.video_id', $videoID)
                ->groupBy(DB::raw('DATE(video_history.created_at)'))->get();
    }
    
    public function channelTraffic($channelID, $fromDate = null, $toDate = null)
    {
        $this->model = new VideoHistoryModel();
        return $this->model->select(DB::raw('COUNT(*) AS user_count'), 'channels.name AS channel', DB::raw('DATE(video_history.created_at) AS date_date'))
                ->join('videos', 'videos.uuid', '=', 'video_history.video_id')
                ->join('channels', 'channels.uuid', '=', 'videos.channel_id')
                ->where(function($query) use ($fromDate)
                {
                    if($fromDate != null)
                    {
                        $query->where(DB::raw('DATE(video_history.created_at)'), '>=', date("Y-m-d", strtotime($fromDate)));
                    }
                })
                ->where(function($query) use ($toDate)
                {
                    if($toDate != null)
                    {
                        $query->where(DB::raw('DATE(video_history.created_at)'), '<=', date("Y-m-d", strtotime($toDate)));
                    }
                })
                ->where('channels.uuid', $channelID)
                ->groupBy(DB::raw('DATE(video_history.created_at)'))->get();
    }
}