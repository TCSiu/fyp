<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Passport\Token;

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
		// $auth_user = $request->cookie('auth_user');
		// $access_token = session('token.access_token');
		
		// $token_parts = explode('.', $access_token);
		// $token_header = $token_parts[1];
		// $token_header_json = base64_decode($token_header);
		// $token_header_array = json_decode($token_header_json, true);
		// $token_id = $token_header_array['jti'];

		// $token = Token::find($token_id)->user;

		if (Auth::guard('api')->check()) {
			return $next($request);
		} else {
			return redirect('login');
		}
	}
}
