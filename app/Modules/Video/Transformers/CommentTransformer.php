<?php namespace App\Modules\Video\Transformers;

use App\Modules\Managers\Comment\CommentModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;
use App\Modules\Video\Transformers\ReplyCommentTransformer;
use App\Modules\User\Transformers\UserTransformer;

class CommentTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = ['user', 'reply'];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(CommentModel $comment)
    {
        return[
            'uuid'          => $comment->uuid,
            'video_id'      => $comment->video_id,
            'comment'       =>  $comment->comment,
            'created_at'       => date("d M Y H:i:s", strtotime($comment->created_at)),
            'user_id'           => $comment->user_id
        ];
    }
    
    public function includeUser(CommentModel $comment)
    {
        return $this->item($comment->user, new UserTransformer());
    }
    
    public function includeReply(CommentModel $comment)
    {
        return $this->collection($comment->reply, new ReplyCommentTransformer());
    }
}
