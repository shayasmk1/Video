<?php namespace App\Modules\Video\Transformers;

use App\Modules\Managers\ReplyComment\ReplyCommentModel;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;


class ReplyCommentTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];
    

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(ReplyCommentModel $comment)
    {
        return[
            'uuid'          => $comment->uuid,
            'video_id'      => $comment->video_id,
            'cpmment_id'      => $comment->comment_id,
            'reply'             =>  $comment->comment,
            'created_at'       => date("d M Y H:i:s", strtotime($comment->created_at)),
            'user_id'           => $comment->user_id
        ];
    }
    
}
