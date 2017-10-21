<?php namespace App\Modules\Managers\UserTag;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface UserTagRepositoryInterface extends RepositoryInterface
{
   public function insertData($data);
   public function insertCustomTag($data);
   public function updateData($data, $id);
   public function deleteData($id);
}