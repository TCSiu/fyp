<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $api_token = request()->header('apiToken');
        $auth_staff = Staff::where('api_token',$api_token)->get();

        if(!count($auth_staff)){
            return response(['message' => 'authentication error', 'apiToken' => $auth_staff]);
        }
        $request->attributes->set('auth_user', $auth_staff);
        return $next($request);
    }
}
