<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccessToken;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private static function allowedLogin(User $user = null){
        if(isset($user)){
            session('access_token', $user->access_token);
			return redirect(route('menu'));
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
        $user_id = $user->id;
		$token = Hash::make(date('YmdHisu'));
        $expiry_date = date('Y-m-d H:i:s', strtotime('+1 hours'));
        $access_token = AccessToken::create(['user_id' => $user_id, 'access_token' => $token, 'expiry_date' => $expiry_date]);
        // $access_token = AccessToken::create(['user_id' => $user_id, 'access_token' => $token]);
		return view('/menu')->with('title', 'Menu')->with('access_token', $access_token->access_token);
	}

	public function login(LoginRequest $request){
        if($request->isMethod('post')){
            $username = $request->auth_username;
            $password = $request->auth_password;
            $error = 'Unknown username. Please enter again.';
            if(isset($user) && $user instanceof User){
                if(!password_verify($password, $user->password)){
                    $error = 'Wrong password. Please enter again.';
                }
                static::allowedLogin($user);
            }
            $user = User::where('name', $username)->first();
            return view('login')->with('errors', [$error]);
        }
        return view('login');
	}
}