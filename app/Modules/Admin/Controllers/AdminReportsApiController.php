<?php namespace App\Modules\Admin\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Symfony\Component\DomCrawler\Form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Modules\Managers\User\UserRepositoryInterface;
use App\Modules\Managers\VideoHistory\VideoHistoryRepositoryInterface;
use App\Modules\Admin\Validators\AdminTagValidator;
use App\Modules\Helper\Helper;
use App\Modules\Admin\Transformers\AdminTagTransformer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class AdminReportsApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, UserRepositoryInterface $userRepo, VideoHistoryRepositoryInterface $videoHistoryRepo)
    {
        $this->user = $userRepo;
        $this->videoHistory = $videoHistoryRepo;
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function userStatisticsOverTags($userID)
    {
        $report = $this->user->getUserStatisticsOverTags($userID);
        return response()->json($report, 200);
    }
    
    public function tagStatisticsOverUser(Request $request)
    {
        $data = $request->get('data');
        $report = $this->user->getTagStatisticsOverUsers($data['from_date'], $data['to_date'], $data['tag_id']);
        return response()->json($report, 200);
    }
    
    public function videoTraffic(Request $request, $videoID)
    {
        $data = $request->get('data');
        $report = $this->videoHistory->videoTraffic($videoID, $data['from_date'], $data['to_date']);
        return response()->json($report, 200);
    }
    
    public function userStatisticsOverTagsChannel($userID)
    {
        $report = $this->user->getUserStatisticsOverTagsChannel($userID);
        return response()->json($report, 200);
    }
    
    
    public function channelTraffic(Request $request, $channelID)
    {
        $data = $request->get('data');
        $report = $this->videoHistory->channelTraffic($channelID, $data['from_date'], $data['to_date']);
        return response()->json($report, 200);
    }
}