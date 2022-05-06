<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AccessToken;
use Illuminate\Http\Request;

class TokenAuth
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
        $access_token = $request->cookie('access_token');
        $auth_token = AccessToken::where('access_token', $access_token)->first();

        if(isset($auth_token) && $auth_token instanceof AccessToken){
            if($auth_token->isExpired()){
                $auth_token = $auth_token->renewSimilar();
            }
            session(['auth_token' => $auth_token]);
            return $next($request);
        }
        return redirect('login');
    }
}
