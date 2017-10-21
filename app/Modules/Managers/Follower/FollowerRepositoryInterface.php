<?php namespace App\Modules\Managers\Follower;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface FollowerRepositoryInterface extends RepositoryInterface
{
   //public function model();
   public function followUser($userByID, $userToID);
   public function unfollowUser($userByID, $userToID);
}