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
namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Modules\ApiBaseController;
use App\Modules\Managers\SessionToken\SessionTokenModel;
use Illuminate\Http\Response;
use Carbon\Carbon;

class GeneralApiAccessMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next)
    {
        $this->token = new SessionTokenModel();
        if($request->exists('token') && trim($request->get('token')) != '' && $request->exists('client_id') && trim($request->get('client_id')) != '')
        {
            $token = $request->get('token');
            $clientID = $request->get('client_id');
            $res = $this->token->where('token', $token)->where('client_id', $clientID)
            ->where(function($query)
            {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', Carbon::now());
            })->first();
            
            if(!$res || $res->user->registration_type != 'general')
            {
                return response()->json(['error' => 401, 'error_description' => ['You are not authorized. Please login'], 'status' => 401, 'message' => 'dashboard.success']);
            }
            
            $request->request->add(['id' => $res->user_id]);
            return $next($request);
        }
        
        return response()->json(['error' => 401, 'error_description' => ['You are not authorized. Please login'], 'status' => 401, 'message' => 'dashboard.success']);
    }
}
