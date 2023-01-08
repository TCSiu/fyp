<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Company;
use App\Http\Requests\WebRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public static function messageDecode(String $msg){
		$errors 	= 	json_decode($msg, true);
		$messages 	= 	[];
		foreach ($errors as $k => $row) {
			foreach ($row as $kk => $rrow) {
				$messages[] = $rrow;
			}
		}
		return $messages;
	}

	public function register(Request $request){
		if($request->isMethod('post')){
			// try{
			// 	$http = new \GuzzleHttp\Client;
			// 	$response = $http->post('http://localhost/api/register', [
			// 		'headers'	=>	[
			// 			'Authorization'	=>	'Bearer'.session()->get('token.access_token')
			// 		],
			// 		'query'		=>	$request->all()
			// 	]);
			// 	$result	=	json_decode($response->getBody(), true);
			// 	// return dd($result);
			// 	return redirect(route('panel'))->with('title', 'Panel');
			// }catch(\Exception $e){
			// 	$response 	= 	json_decode($e->getResponse()->getBody()->getContents(), true);
			// 	$errors 	=	$response['data'];
			// 	$messages 	= 	[];
			// 	foreach ($errors as $k => $row) {
			// 		foreach ($row as $kk => $rrow) {
			// 			$messages[] = $rrow;
			// 		}
			// 	}
			// 	// return dd($messages);
			// 	return redirect()->back()->with('errors', $messages);
			// }

			$company_info = $request->only(['company_name', 'office_address', 'office_email', 'office_phone', 'warehouse_address1', 'warehouse_address2', 'lat', 'lng']);
			$admin_account = $request->only(['username', 'password', 'password_confirmation', 'first_name', 'last_name', 'email', 'phone', 'type', 'company_id']);
			$company_validator = Validator::make($company_info, [
				'company_name'          =>  'required|string|max:255|unique:company',
				'office_address'        =>  'required|string|max:255',
				'office_email'          =>  'required|string|email|max:255|unique:company',
				'office_phone'          =>  'required|string|max:20',
				'warehouse_address1'    =>  'required|string|max:255',
				'warehouse_address2'    =>  'string|max:255|nullable',
				'lat'         			=>  'required|numeric|max:255',
				'lng'         			=>  'required|numeric|max:255',
			]);
			$admin_validator = Validator::make($admin_account, [
				'username'              =>  'required|string|min:5|max:20|unique:account',
				'password'              =>  'required|confirmed|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/', 
				'email'                 =>  'required|string|email|max:255|unique:account',
				'phone'                 =>  'required|string|max:20',
				'first_name'            =>  'string|max:255',
				'last_name'             =>  'string|max:255',
			]);
			if($company_validator->fails()){
				$messages = $this->messageDecode($company_validator->errors());
				return redirect()->back()->with('errors', $messages)->withInput();;
			}
			if($admin_validator->fails()){
				$messages = $this->messageDecode($admin_validator->errors());
				return redirect()->back()->with('errors', $messages)->withInput();;
			}
			$company = Company::create($company_info);
			$admin_account['password'] = password_hash($admin_account['password'], PASSWORD_DEFAULT);
			$admin_account['type'] = 'admin';
			$admin_account['company_id'] = $company->id;
			$account = Account::create($admin_account);
			// $token = $account->createToken('FYP')->accessToken;
			Auth::login($account);
			// Cookie::queue('access_token', $token);
			Cookie::queue('company', $company);
			return redirect(route('panel'))->with('title', 'Panel');
		}
		// if(Auth::check() && Cookie::has('access_token')){
		// 	return redirect(route('panel'))->with('title', 'Panel');
		// }
		return view('register');
	}

	public function login(Request $request){
		if($request->isMethod('post')){
			// try{
			// 	$http = new \GuzzleHttp\Client;
			// 	$response = $http->post('http://localhost/api/login', [
			// 		'headers'	=>	[
			// 			'Authorization'	=>	'Bearer'.session()->get('token.access_token')
			// 		],
			// 		'query'		=>	$request->all()
			// 	]);
			// 	$result		=	json_decode($response->getBody(), true);
			// 	session()->put(['token.access_token' 	=> $result['data']['token']]);
			// 	session()->put(['username'				=> $result['data']['account']]);
			// 	return redirect(route('panel'))->with('title', 'Panel');
			// }catch(\Exception $e){
			// 	$response 	= 	json_decode($e->getResponse()->getBody()->getContents(), true);
			// 	$errors 	=	$response['data'];
			// 	return redirect()->back()->with('errors', $errors);
			// }
			if(Auth::attempt(['username'   =>  $request->username, 'password'   =>  $request->password])){
				$account 	= Auth::user();
				$company 	= Company::where('id', $account->company_id)->first();
				// $token 		= $account->createToken('FYP')->accessToken;
				// Cookie::queue('access_token', $token, 1000);
				Cookie::queue('company', $company, 1000);
				return redirect(route('panel'))->with('title', 'Panel');
			}else{
				return redirect()->back()->with('errors', 'Unauthorised!')->withInput();;
			}
		}
		// if(Auth::check() && Cookie::has('access_token')){
		// 	return redirect(route('panel'))->with('title', 'Panel');
		// }
		return view('login');
	}

	public function logout(WebRequest $request){
		Auth::logout();
		// Cookie::forget('access_token');
		Cookie::forget('company');
		// session()->forget('token.access_token');
		// session()->forget('username');
		return redirect('/');
	}
}