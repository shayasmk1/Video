<?php namespace App\Modules\Managers\SessionToken;

use App\Modules\ModelRepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface SessionTokenRepositoryInterface extends ModelRepositoryInterface
{
   public function insertData($data);
}