<?php namespace App\Modules\Managers\VideoLog;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface VideoLogRepositoryInterface extends RepositoryInterface
{
   public function model();
   public function insertVideoDetails($data);
}