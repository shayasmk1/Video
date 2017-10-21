<?php namespace App\Modules\Managers\ChannelLog;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface ChannelLogRepositoryInterface extends RepositoryInterface
{
   public function model();
   public function insertData($data);
}