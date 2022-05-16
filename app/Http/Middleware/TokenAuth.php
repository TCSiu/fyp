<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
		$auth_user = $request->cookie('auth_user');
		$access_token = $request->cookie('access_token');
		$auth_token = AccessToken::where('access_token', $access_token)->first();

		if(isset($auth_token) && $auth_token instanceof AccessToken){
			if($auth_token->isExpired()){
				$auth_token = $auth_token->renewSimilar();
				Cookie::queue('access_token', $auth_token->access_token, 60 * 24);
				Cookie::queue('auth_user', $auth_user, 60 * 24);
			}
			return $next($request);
		}
		return redirect('login');
	}
}
