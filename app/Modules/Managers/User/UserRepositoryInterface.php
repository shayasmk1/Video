<?php namespace App\Modules\Managers\User;


use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Contracts\Repositories
 */
interface UserRepositoryInterface extends RepositoryInterface
{
   public function insertData($data);
   public function activateUser($confirmationCode, $reconfirmCode, $UUID);
   public function getCurrentUser($email);
}