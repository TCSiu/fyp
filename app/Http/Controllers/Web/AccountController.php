<?php

namespace App\Http\Controllers\Web;

use App\Models\Account;
use App\Models\Company;
use App\Http\Requests\WebRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AccountController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public static function messageDecode(String $msg)
    {
        $errors 	= json_decode($msg, true);
        $messages 	= [];
        foreach ($errors as $k => $row) {
            foreach ($row as $kk => $rrow) {
                $messages[] = $rrow;
            }
        }
        return $messages;
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $company_validator = Validator::make($request->all(), [
                'company_name'          => 'required|string|max:255|unique:company',
                'office_address'        => 'required|string|max:255',
                'office_email'          => 'required|string|email|max:255|unique:company',
                'office_phone'          => 'required|string|max:20',
                'warehouse_address1'    => 'required|string|max:255',
                'warehouse_address2'    => 'string|max:255|nullable',
                'lat'                   => 'required|numeric|max:255',
                'lng'                   => 'required|numeric|max:255',
            ]);
            $admin_validator = Validator::make($request->all(), [
                'username'              => 'required|string|min:5|max:20|unique:account',
                'password'              => 'required|confirmed|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/',
                'sex'                   => 'required|string',
                'email'                 => 'required|string|email|max:255|unique:account',
                'phone'                 => 'required|string|max:20',
                'first_name'            => 'string|max:255',
                'last_name'             => 'string|max:255',
            ]);

            if ($company_validator->fails()) {
                $messages = $this->messageDecode($company_validator->errors());
                return redirect()->back()->with('errors', $messages)->withInput();
            }
            if ($admin_validator->fails()) {
                $messages = $this->messageDecode($admin_validator->errors());
                return redirect()->back()->with('errors', $messages)->withInput();
            }

            $company_info = $company_validator->validated();
            $admin_account = $admin_validator->validated();

            $company = Company::create($company_info);
            $admin_account['password'] = password_hash($admin_account['password'], PASSWORD_DEFAULT);
            $admin_account['type'] = 'admin';
            $admin_account['company_id'] = $company->id;
            $account = Account::create($admin_account);

            Auth::login($account);
            Cookie::queue('company', $company);
            return redirect(route('panel'))->with('title', 'Panel');
        }
        if (Auth::check() && Cookie::has('company')) {
            return redirect(route('panel'))->with('title', 'Panel');
        }
        return view('register');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'username' => 'required|exists:account,username',
                'password' => 'required',
            ], [
                'required' => 'The :attribute field is required.',
                'exists' => 'The :attribute does not exist in the database.'
            ]);
            if ($validation->fails()) {
                $messages = $this->messageDecode($validation->errors());
                return redirect()->back()->with('errors', $messages)->withInput();
            }

            if (Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])) {
                $account = Auth::user();
                if (!(in_array($account->type, ['admin']) > 0)) {
                    Auth::logout();
                    Cookie::forget('company');
                    return redirect()->back()->with('errors', ['Unauthorised!'])->withInput();
                }
                $company = Company::where('id', $account->company_id)->first();

                Cookie::queue('company', $company, 1200);
                return redirect(route('panel'))->with('title', 'Panel');
            } else {
                return redirect()->back()->with('errors', ['Wrong Password!'])->withInput();
            }
        }
        if (Auth::check() && Cookie::has('company')) {
            return redirect(route('panel'))->with('title', 'Panel');
        }
        return view('login');
    }

    public function logout(WebRequest $request)
    {
        Auth::logout();
        Cookie::forget('company');
        return redirect('/');
    }
}
