<?php namespace App\Modules\Managers\VideoHistory;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface VideoHistoryRepositoryInterface extends RepositoryInterface
{
   public function insertData($data);
}