<?php namespace App\Modules\Managers\Tag;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface TagRepositoryInterface extends RepositoryInterface
{
   public function model();
   public function insertData($data);
   public function updateData($data, $id);
   public function deleteData($id);
}