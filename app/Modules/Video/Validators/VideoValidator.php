<?php

/*
*
* INFOATCLUSTER TECHNOLABS LLP ("COMPANY") CONFIDENTIAL
* Copyright (c) 2016-2017 INFOATCLUSTER TECHNOLABS LLP, All Rights Reserved.
*
* NOTICE:  All information contained herein is, and remains the property of INFOATCLUSTER TECHNOLABS LLP. The intellectual and technical concepts contained
* herein are proprietary to INFOATCLUSTER TECHNOLABS LLP and may be covered by Indian and Foreign Patents, patents in process, and are protected by trade secret or copyright law.
* Dissemination of this information or reproduction of this material is strictly forbidden unless prior written permission is obtained
* from COMPANY.  Access to the source code contained herein is hereby forbidden to anyone except current COMPANY employees, managers or contractors who have executed 
* Confidentiality and Non-disclosure agreements explicitly covering such access.
*
* The copyright notice above does not evidence any actual or intended publication or disclosure  of  this source code, which includes  
* information that is confidential and/or proprietary, and is a trade secret, of  COMPANY.   ANY REPRODUCTION, MODIFICATION, DISTRIBUTION, PUBLIC  PERFORMANCE, 
* OR PUBLIC DISPLAY OF OR THROUGH USE  OF THIS  SOURCE CODE  WITHOUT  THE EXPRESS WRITTEN CONSENT OF COMPANY IS STRICTLY PROHIBITED, AND IN VIOLATION OF APPLICABLE 
* LAWS AND INTERNATIONAL TREATIES.  THE RECEIPT OR POSSESSION OF  THIS SOURCE CODE AND/OR RELATED INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS  
* TO REPRODUCE, DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO MANUFACTURE, USE, OR SELL ANYTHING THAT IT  MAY DESCRIBE, IN WHOLE OR IN PART.                
*
*
* @author Mohammed Shayas M K
* 
*/

namespace App\Modules\Video\Validators;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Modules\Helper\Helper;
//use App\Modules\Managers\User\UserRepositoryInterface;

class VideoValidator
{
    public function __construct()
    {
        //parent::__construct($attributes);
        $this->helper = new Helper();
    }
    
    public function store($data, $video)
    {
        $columns = array('name', 'type', 'url', 'privacy_option_id', 'channel_id');
        $helper = $this->helper->checkAllRequiredValues(array_flip($columns), $data);
        if(!$helper)
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        $validation = $validator = Validator::make(
                [
                 'Name'                     => trim($data['name']),
                 'Type'                     => trim($data['type']),
                 'url'                      => trim($data['url']),
                 'Privacy Option'           => trim($data['privacy_option_id']),
                 'Channel'                  => trim($data['channel_id']),
                 'Video'                    => $video],
                [
                 'Name'               => 'required|min:2',
                 'Type'               => 'required',
                 'url'                => 'required',
                 'Privacy Option'     => 'required',
                 'Channel'            => 'required',
                 'Video'              => 'required|mimes:mp4,avi,3gp,mpeg,mkv,dat,vob,webm'
                 ]
        );
        
       
        if($validation->fails())
        {
            
            $errors = $validation->errors();
            $errors = $errors->toArray();
            
            foreach($errors AS $error)
            {
                foreach($error AS $eachError)
                {
                    $return['errors'][] = $eachError;
                }
            }
            return $return;
        }
    }
    
    public function videoLogInsert($data, $videoID)
    {
        $columns = array('video_time');
        $helper = $this->helper->checkAllRequiredValues(array_flip($columns), $data);
        if(!$helper)
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        $validation = $validator = Validator::make(
                [
                 'Video'                     => trim($videoID),
                 'Time'                     => trim($data['video_time'])],
                [
                 'Video'               => 'required',
                 'Time'               => 'required|date_format:H:i:s'
                 ]
        );
        
       
        if($validation->fails())
        {
            
            $errors = $validation->errors();
            $errors = $errors->toArray();
            
            foreach($errors AS $error)
            {
                foreach($error AS $eachError)
                {
                    $return['errors'][] = $eachError;
                }
            }
            return $return;
        }
    }
    
    public function pasteLink($data)
    {
        $columns = array('url', 'type');
        $helper = $this->helper->checkAllRequiredValues(array_flip($columns), $data);
        if(!$helper)
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        $validation = $validator = Validator::make(
                [
                 'URL'              => trim($data['url']),
                 'Type'             => trim($data['type'])],
                [
                 'URL'              => 'required',
                 'Type'             => 'required'
                 ]
        );
        
       
        if($validation->fails())
        {
            
            $errors = $validation->errors();
            $errors = $errors->toArray();
            
            foreach($errors AS $error)
            {
                foreach($error AS $eachError)
                {
                    $return['errors'][] = $eachError;
                }
            }
            return $return;
        }
    }
    
    public function storeGoogle($data, $video)
    {
        $columns = array('name', 'privacy_option_id', 'channel_id');
        $helper = $this->helper->checkAllRequiredValues(array_flip($columns), $data);
        if(!$helper)
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        $validation = $validator = Validator::make(
                [
                 'Name'                     => trim($data['name']),
                 'Privacy Option'           => trim($data['privacy_option_id']),
                 'Channel'                  => trim($data['channel_id']),
                 'Video'                    => $video],
                [
                    'Name'               => 'required|min:2',
                 'Privacy Option'     => 'required',
                 'Channel'            => 'required',
                 'Video'              => 'required|mimes:mp4,avi,3gp,mpeg,mkv,dat,vob,webm'
                 ]
        );
        
       
        if($validation->fails())
        {
            
            $errors = $validation->errors();
            $errors = $errors->toArray();
            
            foreach($errors AS $error)
            {
                foreach($error AS $eachError)
                {
                    $return['errors'][] = $eachError;
                }
            }
            return $return;
        }
    }
}