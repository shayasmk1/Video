<?php namespace App\Modules\Managers\ReplyComment;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface ReplyCommentRepositoryInterface extends RepositoryInterface
{
   public function insertData($data);
   public function updateData($data, $id);
   public function deleteData($id);
}