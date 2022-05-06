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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private static function setCookie(String $name, String $value){
        $response = new Response('Hello World');
        // $token_cookie = Cookie::make($name, $value, 60);
        return response('fyp')->cookie($name, $value, 60);
    }

    private static function allowedLogin(Request $request, User $user = null){
        if(isset($user)){
            $access_token = $user->checkTokenExpiry('website');            
            static::setCookie('access_token', $access_token->access_token);
			return redirect(route('panel'))->with('title', 'Panel');
        }
        return false;
    }

	public function register(Request $request){
		$data = $request->all();
		$rules = [
			'name' => ['required', 'string', 'min:5', 'max:255', 'unique:users'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], 
			'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'], 
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
			return redirect(route('register'))->with('errors', $messages)->withinput();
		}
		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		$data['is_admin'] = true;
		$user = User::create($data);
        $access_token = AccessToken::createToken($user->id, 'website');
        // $request->session()->put('access_token', $access_token->access_token);
        static::setCookie('access_token', $access_token->access_token);
		return redirect(route('panel'))->with('title', 'Panel');
	}

	public function login(LoginRequest $request){
        if($request->isMethod('post')){
            $username = $request->auth_username;
            $password = $request->auth_password;
            $user = User::where('name', $username)->first();
            $error = 'Unknown username. Please enter again.';
            if(isset($user) && $user instanceof User){
                if(password_verify($password, $user->password)){
                    return static::allowedLogin($request, $user);
                }
                $error = 'Wrong password. Please enter again.';
            }
            return view('login')->with('errors', [$error]);
        }
        return view('login');
	}
}