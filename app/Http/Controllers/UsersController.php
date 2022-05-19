<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccessToken;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UsersController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public static function checkToken(Request $request, string $target = ''){
		if($request->hasCookie('auth_user')){
			$data = $request->cookie('auth_user');
			$cookie_user = json_decode($data, true);
			$user = User::where('id', $cookie_user['id'])->first();
			if(isset($user)){
				return static::allowedLogin($request, $user);
			}
		}
		return view($target);
	}

	private static function allowedLogin(Request $request, User $user = null){
		if(isset($user)){
			$access_token = $user->checkTokenExpiry('website');
			Cookie::queue('access_token', $access_token->access_token, 60 * 24);
			Cookie::queue('auth_user', $user, 60 * 24);	   
			return redirect(route('panel'))->with('title', 'Panel');
		}
		return false;
	}

	public function register(Request $request){
		if($request->isMethod('post')){
			$data = $request->all();
			$rules = [
				'name' => 'required|string|min:5|max:20|unique:users',
				'email' => 'required|string|email|max:255|unique:users', 
				'password' => 'required|confirmed|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/', 
			];
			$validator = Validator::make($data, $rules);
	
			if($validator->fails()){
				$errors = $validator->errors()->toArray();
				$messages = [];
				foreach ($errors as $k => $row) {
					foreach ($row as $kk => $rrow) {
						$messages[] = $rrow;
					}
				}
				return view('register')->with('errors', $messages);
			}
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
			$data['is_admin'] = true;
			$user = User::create($data);
			$access_token = AccessToken::createToken($user->id, 'website');
			Cookie::queue('access_token', $access_token->access_token, 60 * 24);
			Cookie::queue('auth_user', $user, 60 * 24);	
			return redirect(route('panel'))->with('title', 'Panel');
		}
		return static::checkToken($request, 'register');
	}

	public function login(LoginRequest $request){
		if($request->isMethod('post')){
			$username = $request->auth_username;
			$password = $request->auth_password;
			$user = User::where('name', $username)->first();
			$error = 'Unknown username. Please enter again.';
			if(isset($user) && $user instanceof User){
				$error = 'Password cannot be empty. Please enter again.';
				if(!is_null($password) && is_string($password)){
					if(password_verify($password, $user->password)){
						return static::allowedLogin($request, $user);
					}
					$error = 'Wrong password. Please enter again.';
				}
			}
			return view('login')->with('errors', [$error]);
		}
		return static::checkToken($request, 'login');
	}

	public function logout(Request $request){
		if($request->hasCookie('auth_user')){
			Cookie::queue(Cookie::forget('auth_user'));
		}
		if($request->hasCookie('access_token')){
			Cookie::queue(Cookie::forget('access_token'));
		}
		return redirect('/');
	}
}