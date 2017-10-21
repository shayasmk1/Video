<?php namespace App\Modules\Managers\Video;

use App\Modules\ModelRepositoryInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface VideoRepositoryInterface extends RepositoryInterface
{
   public function model();
   public function insertData($data);
   public function updateData($data, $id);
   public function deleteData($id);
}