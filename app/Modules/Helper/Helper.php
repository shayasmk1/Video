<?php
namespace App\Modules\Helper;
use Webpatser\Uuid\Uuid;

class Helper
{
    public function checkAllRequiredValues($values, $data)
    {
        $count = 0;
        foreach($values AS $key => $value)
        {
            if(!array_key_exists($key, $data))
            {
                return 0;
                exit;
            }
            $count++;
        }
        return 1;
        exit;
    }
    
    function changeSerialzeArrayValues($data, $type = null)
    {
        
        $return = array();
        foreach($data AS $key => $value)
        {
            $return[$value['name']] = $value['value'];
        }
        if($type == 'create')
        {
            $return['UUID'] = $this->addUuid();
        }
        return $return;
    }
    
    function addUuid()
    {
        return Uuid::generate()->string;
    }
    
    function clearEmptyValues($data, $type = null)
    {
        $each = array();
        foreach($data AS $key => $value)
        {
            if(trim($value) != '')
            {
                $each[$key] = $value;
            }
        }
        
        if(!isset($data['id']) && $type == 'create')
        {
            $each['id'] = $this->addUuid();
        }
        
        return $each;
    }
}