<?php namespace App\Modules\Managers\User;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\User\UserRepositoryInterface;
use App\Modules\Helper\Helper;

class UserCategoryRepository extends BaseRepository implements UserRepositoryInterface
{
    
    function model()
    {
        return "App\\Modules\\Managers\\UserCategory\\UserCategoryModel";
    }
    
    public function insertData($data)
    {
        return $this->create($data);
    }
    
    public function updateData($data, $id)
    {
        return $this->update($data, $id);
    }
    
    public function deleteData($id)
    {
        return $this->deleteWhere(['uuid' => $id]);
    }
    
    public function activateUser($confirmationCode, $reconfirmCode, $UUID)
    {
        $user = $this->findWhere(['uuid' => $UUID, 'confirmation_code' => $confirmationCode, 'reconfirm_code' => $reconfirmCode])->first();
        if($user)
        {
            $data['active'] = 1;
            $this->update($data, $UUID);
        }
    }
    
    public function getCurrentUser($email)
    {
        return $this->findWhere(['email' => $email])->first();
    }
    
    public function getAllUsers()
    {
        $this->userModel = new UserModel();
        return $this->userModel->where('active', 1)->paginate(10);
    }
    
    public function searchUsers($name, $noOfResults)
    {
        $this->model = new UserModel();
        return $this->model->where('first_name', 'LIKE', $name . '%')
                ->orWhere('last_name', 'LIKE', $name . '%')
                ->orWhere('email' , 'LIKE', $name . '%')
                ->paginate($noOfResults);
    }
}