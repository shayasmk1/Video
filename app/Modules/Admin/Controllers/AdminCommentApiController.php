<?php namespace App\Modules\Video\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Managers\PrivacyOption\PrivacyOptionRepositoryInterface;
use App\Modules\Managers\Comment\CommentRepositoryInterface;
use App\Modules\Video\Validators\VideoValidator;
use App\Modules\Video\Validators\CommentValidator;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use App\Modules\Video\Transformers\PrivacyOptionTransformer;
use App\Modules\Video\Transformers\CommentTransformer;
use Illuminate\Support\Facades\Storage;

class AdminCommentApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, VideoRepositoryInterface $videoRepo, PrivacyOptionRepositoryInterface $privacyOptionsRepo, CommentRepositoryInterface $commentRepo)
    {
        $this->video = $videoRepo;
        $this->videoValidator = new VideoValidator();
        $this->commentValidator = new CommentValidator($videoRepo);
        $this->privacyOptions = $privacyOptionsRepo;
        $this->comment = $commentRepo;
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function store(Request $request, $videoID)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $data = $request->get('data');
        $validation = $this->commentValidator->store($data, $videoID);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        $data['video_id'] = $videoID;
        
        $comment = $this->comment->insertData($data);
        return $this->respondWithItem($comment, new CommentTransformer());
    }
    
    public function index(Request $request, $videoID)
    {
        $comments = $this->comment->findAllCommentsOfVideo($videoID);
        return $this->respondWithCollection($comments, new CommentTransformer());
    }
    
    public function show(Request $request, $videoID, $commentID)
    {
        $comment = $this->comment->findWhere(['video_id'  => $videoID, 'uuid' => $commentID])->first();
        if(!$comment)
        {
            return $this->errorWrongArgs(['Comment not found']);
        }
        return $this->respondWithItem($comment, new CommentTransformer());
    }


    public function update(Request $request, $videoID, $commentID)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        
        $data = $request->get('data');
        $validation = $this->commentValidator->store($data, $videoID);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $comment = $this->comment->findWhere(['video_id'  => $videoID, 'uuid' => $commentID])->first();
        if(!$comment)
        {
            return $this->respondWithError([['Comment not found']], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $comment = $this->comment->updateData($data, $commentID);
        
        return $this->respondWithBoolean($comment, new CommentTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $comment = $this->channel->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$comment)
        {
            return $this->errorWrongArgs(['Comment not found']);
        }
        $comment = $this->comment->deleteData($id);
        
        return $this->respondWithBoolean($comment, new CommentTransformer());
    }
    
    
}