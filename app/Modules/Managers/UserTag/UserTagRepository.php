<?php namespace App\Modules\Managers\UserTag;

//use App\Modules\AbstractRepository;
use App\Modules\Managers\UserTag\UserTagRepositoryInterface;
use App\Modules\Helper\Helper;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Modules\Managers\Tag\TagModel;

class UserTagRepository extends BaseRepository implements UserTagRepositoryInterface
{
//    function __construct()
//    {
//        
//        $this->helper = new Helper();
//    }
    
    function model()
    {
        return "App\\Modules\\Managers\\UserTag\\UserTagModel";
    }
    
    public function insertData($data)
    {
        $userTag = $this->findWhere(['user_id' => $data['user_id'], 'tag_id' => $data['tag_id']])->first();
        if($userTag)
        {
            return 1;
            exit;
        }
        DB::beginTransaction();
        try 
        {
            $video =  $this->create($data);
            if(!$video)
            {
                return 0;
            }
            
            DB::commit();
            return 1;
        }
        catch(ValidationException $e)
        {
            DB::rollback();
            return 0;
        }
    }
    
    public function insertCustomTag($data)
    {
        $this->helper = new Helper();
        $this->model = new UserTagModel();
        $this->tag = new TagModel();
        $tag = $this->tag->where('name', $data['name'])->first();
        if($tag)
        {
            return 0;
            exit;
        }
        
        DB::beginTransaction();
        try 
        {
            $tag = $this->tag->create($data);
            if(!$tag)
            {
                return 0;
                exit;
            }
            
            $data1['tag_id'] = $data['uuid'];
            $data1['uuid'] = $this->helper->addUuid();
            $data1['user_id'] = $data['user_id'];
            $userTag =  $this->model->create($data1);
            if(!$userTag)
            {
                return 0;
            }
            
            DB::commit();
            return 1;
        }
        catch(ValidationException $e)
        {
            DB::rollback();
            return 0;
        }
    }
    
    public function updateData($data, $id)
    {
        return $this->update($data, $id);
    }
    
    public function deleteData($id)
    {
        return $this->deleteWhere(array('tag_id' => $id));
    }
    
}