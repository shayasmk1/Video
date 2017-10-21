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

namespace App\Modules\User\Validators;
use Illuminate\Support\Facades\Validator;
use App\Modules\Managers\User\UserManager;
use Illuminate\Support\Facades\Session;
use App\Modules\Helper\Helper;
//use App\Modules\Managers\User\UserRepositoryInterface;

class UserValidator
{
    public function __construct()
    {
        //parent::__construct($attributes);
        //$this->user = new UserManager();
        $this->helper = new Helper();
        
    }
//    
    public function store($data)
    {
        $columns = array('first_name', 'last_name', 'phone', 'email', 'password');
        $helper = $this->helper->checkAllRequiredValues(array_flip($columns), $data);
        
        if(!$helper)
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        
        $validation = $validator = Validator::make(
                ['First Name'             => trim($data['first_name']),
                 'Last Name'              => trim($data['last_name']),
                 'Phone'                  => trim($data['phone']),
                 'Email'                  => trim($data['email']),
                 'Password'               => trim($data['password'])],
                ['First Name'             => 'required|min:2',
                 'Last Name'              => 'required|min:2',
                 'Phone'                  => 'required|min:10',
                 'Email'                  => 'required|email|unique:users,email',
                 'Password'               => 'required|min:6|alpha_dash']
        );
        if($validation->fails())
        {
            $errors = $validation->errors();
            $errors = $errors->toArray();
            
            foreach($errors AS $error)
            {
                $return['errors'][] = $error;
            }
            return $return;
        }
    }
    
    public function update($data)
    {
        
        if(array_key_exists('uuid', $data) || array_key_exists('email', $data) || array_key_exists('password', $data) || array_key_exists('registration_type', $data)|| array_key_exists('registration_reference_id', $data)|| array_key_exists('registration_type', $data))
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        $columns = array('first_name', 'last_name', 'phone');
        $helper = $this->helper->checkAllRequiredValues(array_flip($columns), $data);
        
        if(!$helper)
        {
            $return['errors'][] = 'Something went wrong';
            return $return;
        }
        
        $validation = $validator = Validator::make(
                ['First Name'             => trim($data['first_name']),
                 'Last Name'              => trim($data['last_name']),
                 'Phone'                  => trim($data['phone'])],
                ['First Name'             => 'required|min:2',
                 'Last Name'              => 'required|min:2',
                 'Phone'                  => 'required|min:10']
        );
        if($validation->fails())
        {
            $errors = $validation->errors();
            $errors = $errors->toArray();
            
            foreach($errors AS $error)
            {
                $return['errors'][] = $error;
            }
            return $return;
        }
    }
    
}