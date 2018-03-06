<?php namespace App\Modules\Video\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Managers\Comment\CommentRepositoryInterface;
use App\Modules\Managers\ReplyComment\ReplyCommentRepositoryInterface;
use App\Modules\Video\Validators\VideoValidator;
use App\Modules\Video\Validators\CommentValidator;
use App\Modules\Video\Validators\ReplyCommentValidator;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use App\Modules\Video\Transformers\PrivacyOptionTransformer;
use App\Modules\Video\Transformers\ReplyCommentTransformer;

use Illuminate\Support\Facades\Storage;

class AdminReplyCommentApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, VideoRepositoryInterface $videoRepo, CommentRepositoryInterface $commentRepo, ReplyCommentRepositoryInterface $replyCommentRepo)
    {
        $this->video = $videoRepo;
        $this->comment = $commentRepo;
        $this->replyComment = $replyCommentRepo;
        $this->videoValidator = new VideoValidator();
        $this->replyCommentValidator = new ReplyCommentValidator($videoRepo, $commentRepo);
        $this->comment = $commentRepo;
        
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function store(Request $request, $videoID, $commentID)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $data = $request->get('data');
        $validation = $this->replyCommentValidator->store($data,$videoID, $commentID);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        $data['video_id'] = $videoID;
        $data['comment_id'] = $commentID;
        
        $comment = $this->replyComment->insertData($data);
        return $this->respondWithItem($comment, new ReplyCommentTransformer());
    }
    
    public function index(Request $request, $videoID, $commentID)
    {
        $comments = $this->replyComment->findWhere(['video_id'  => $videoID, 'comment_id'  => $commentID]);
        return $this->respondWithCollection($comments, new ReplyCommentTransformer());
    }
    
    public function show(Request $request, $videoID, $commentID, $replyID)
    {
        $comment = $this->replyComment->findWhere(['video_id'  => $videoID, 'comment_id' => $commentID, 'uuid' => $replyID])->first();
        if(!$comment)
        {
            return $this->errorWrongArgs(['Comment not found']);
        }
        return $this->respondWithItem($comment, new ReplyCommentTransformer());
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
        
        $comment = $this->replyComment->findWhere(['video_id'  => $videoID, 'uuid' => $commentID])->first();
        if(!$comment)
        {
            return $this->respondWithError(['Comment not found'], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $comment = $this->replyComment->updateData($data, $commentID);
        
        return $this->respondWithBoolean($comment, new ReplyCommentTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $comment = $this->replyComment->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$comment)
        {
            return $this->errorWrongArgs(['Comment not found']);
        }
        $comment = $this->replyComment->deleteData($id);
        
        return $this->respondWithBoolean($comment, new ReplyCommentTransformer());
    }
    
}