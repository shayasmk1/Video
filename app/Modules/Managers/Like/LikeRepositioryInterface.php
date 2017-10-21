<?php namespace App\Modules\Managers\Like;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface LikeRepositoryInterface extends RepositoryInterface
{
   public function model();
   public function likeVideo($videoID, $userID);
   public function dislikeVideo($videoID, $userID);
}