<?php namespace App\Modules\Managers\Channel;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface ChannelRepositoryInterface extends RepositoryInterface
{
   public function model();
   public function insertData($data);
}