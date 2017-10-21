<?php namespace App\Modules\Managers\User;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Modules\Managers\User\UserRepositoryInterface;
use App\Modules\Managers\User\UserModel;
use App\Modules\Helper\Helper;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    
    function model()
    {
        return "App\\Modules\\Managers\\User\\UserModel";
    }
//    public function __construct(UserModel $model)
//    {
//        $this->helper = new Helper();
//        $this->model = $model;
//        //parent::__construct($model);
//    }
    
    
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
    
    public function getUserStatisticsOverTags($userID)
    {
        $this->model = new UserModel();
        $sql = "SELECT t1.*, t.name AS tag_name, t.uuid AS tag_id, COUNT(*) AS number_of_videos FROM(SELECT u.first_name,u.last_name,u.email,v.user_id AS video_added_user_id FROM users u "
                . " INNER JOIN video_history vh "
                . " ON u.uuid=vh.user_id "
                . " INNER JOIN videos v "
                . " ON v.uuid=vh.video_id "
                . " WHERE u.uuid=? "
                . " AND v.deleted_at IS NULL"
                . " AND vh.deleted_at IS NULL "
                . " GROUP BY vh.video_id) AS t1 "
                . " INNER JOIN user_tags ut "
                . " ON t1.video_added_user_id=ut.user_id "
                . " INNER JOIN tags t "
                . " ON t.uuid=ut.tag_id "
                . " WHERE t.deleted_at IS NULL"
                . " AND ut.deleted_at IS NULL "
                . " GROUP BY tag_id";
        
        return DB::select($sql, [$userID]);
    }
    
    public function getTagStatisticsOverUsers($fromDate = null, $toDate = null, $tagID = null)
    {
        $where = array();
        $whereHtml = '';
        $whereHtml1 = '';
        if($fromDate != null)
        {
            $whereHtml.= " AND DATE(vh.created_at) >= ? ";
            $where[] = date("Y-m-d", strtotime($fromDate));
        }
        
        if($toDate != null)
        {
            $whereHtml.= " AND DATE(vh.created_at) <= ? ";
            $where[] = date("Y-m-d", strtotime($toDate));
        }
        if($tagID != null && $tagID != '')
        {
            $whereHtml1.= " AND t.uuid = ? ";
            $where[] = $tagID;
        }
       
        $sql = "SELECT t.name AS tag_name, t.uuid AS tag_id, COUNT(*) AS number_of_users FROM(SELECT u.first_name,u.last_name,u.email,v.user_id AS video_added_user_id FROM users u "
                . " INNER JOIN video_history vh "
                . " ON u.uuid=vh.user_id "
                . " INNER JOIN videos v "
                . " ON v.uuid=vh.video_id "
                . " WHERE v.deleted_at IS NULL"
                . " AND vh.deleted_at IS NULL "
                . $whereHtml
                . " GROUP BY vh.video_id,v.user_id) AS t1 "
                . " INNER JOIN user_tags ut "
                . " ON t1.video_added_user_id=ut.user_id "
                . " INNER JOIN tags t "
                . " ON t.uuid=ut.tag_id "
                . " WHERE t.deleted_at IS NULL"
                . " AND ut.deleted_at IS NULL "
                . $whereHtml1
                . " GROUP BY tag_id";
        
        return DB::select($sql, $where);
    }
    
    public function getuserStatisticsOverTagsChannel($userID)
    {
        $this->model = new UserModel();
        $sql = "SELECT t1.*, t.name AS tag_name, t.uuid AS tag_id, "
                . " COUNT(*) AS number_of_channels FROM("
                . " SELECT u.first_name,u.last_name,u.email,c.user_id AS channel_added_user_id FROM users u "
                . " INNER JOIN video_history vh "
                . " ON u.uuid=vh.user_id "
                . " INNER JOIN videos v "
                . " ON v.uuid=vh.video_id "
                . " INNER JOIN channels c "
                . " ON c.uuid=v.channel_id "
                . " WHERE u.uuid=? "
                . " AND v.deleted_at IS NULL"
                . " AND vh.deleted_at IS NULL "
                . " AND c.deleted_at IS NULL "
                . " GROUP BY c.uuid) AS t1 "
                . " INNER JOIN user_tags ut "
                . " ON t1.channel_added_user_id=ut.user_id "
                . " INNER JOIN tags t "
                . " ON t.uuid=ut.tag_id "
                . " WHERE t.deleted_at IS NULL"
                . " AND ut.deleted_at IS NULL "
                . " GROUP BY tag_id";
        
        return DB::select($sql, [$userID]);
    }
    
    public function tagStatisticsOverUserChannel($fromDate = null, $toDate = null, $tagID = null)
    {
        $where = array();
        $whereHtml = '';
        $whereHtml1 = '';
        if($fromDate != null)
        {
            $whereHtml.= " AND DATE(vh.created_at) >= ? ";
            $where[] = date("Y-m-d", strtotime($fromDate));
        }
        
        if($toDate != null)
        {
            $whereHtml.= " AND DATE(vh.created_at) <= ? ";
            $where[] = date("Y-m-d", strtotime($toDate));
        }
        if($tagID != null && $tagID != '')
        {
            $whereHtml1.= " AND t.uuid = ? ";
            $where[] = $tagID;
        }
        $sql = "SELECT t.name AS tag_name, t.uuid AS tag_id, COUNT(*) AS number_of_users FROM(SELECT u.first_name,u.last_name,u.email,v.user_id AS video_added_user_id FROM users u "
                . " INNER JOIN video_history vh "
                . " ON u.uuid=vh.user_id "
                . " INNER JOIN videos v "
                . " ON v.uuid=vh.video_id "
                . " WHERE v.deleted_at IS NULL"
                . " AND vh.deleted_at IS NULL "
                . $whereHtml
                . " GROUP BY vh.video_id,v.user_id) AS t1 "
                . " INNER JOIN user_tags ut "
                . " ON t1.video_added_user_id=ut.user_id "
                . " INNER JOIN tags t "
                . " ON t.uuid=ut.tag_id "
                . " WHERE t.deleted_at IS NULL"
                . " AND ut.deleted_at IS NULL "
                . $whereHtml1
                . " GROUP BY tag_id";
        
        return DB::select($sql, $where);
    }
}