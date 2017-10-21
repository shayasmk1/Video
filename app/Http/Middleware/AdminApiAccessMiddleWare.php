<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Modules\ApiBaseController;
use App\Modules\Managers\SessionToken\SessionTokenModel;
use Illuminate\Http\Response;
use Carbon\Carbon;

class AdminApiAccessMiddleWare
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
            $res = $this->token->with('user')->where('token', $token)->where('client_id', $clientID)
            ->where(function($query)
            {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', Carbon::now());
            })->first();
            
            if(!$res || $res->user->registration_type != 'admin')
            {
                return response()->json(['error' => 401, 'error_description' => [['You are not authorized. Please login']], 'status' => 401, 'message' => 'dashboard.success']);
            }
            $request->request->add(['id' => $res->user_id]);
            return $next($request);
        }
        
        return response()->json(['error' => 401, 'error_description' => [['You are not authorized. Please login']], 'status' => 401, 'message' => 'dashboard.success']);
    }
}
