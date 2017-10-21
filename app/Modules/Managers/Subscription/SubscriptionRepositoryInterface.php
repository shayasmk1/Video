<?php namespace App\Modules\Managers\Subscription;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface SubscriptionRepositoryInterface extends RepositoryInterface
{
   //public function model();
   public function subscribeChannel($videoID, $userID);
   public function unsubscribeChannel($videoID, $userID);
}