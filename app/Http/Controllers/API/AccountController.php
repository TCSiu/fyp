<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\Company;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AccountController extends BaseController
{
    // public function register(Request $request){
    //     $company_info = $request->only(['company_name', 'office_address', 'office_email', 'office_phone', 'warehouse_address1', 'warehouse_address2', 'warehouse_lat', 'warehouse_lng']);
    //     $admin_account = $request->only(['username', 'password', 'password_confirmation', 'first_name', 'last_name', 'email', 'phone', 'type', 'company_id']);
    //     $company_validator = Validator::make($company_info, [
    //         'company_name'          =>  'required|string|max:255|unique:company',
    //         'office_address'        =>  'required|string|max:255',
    //         'office_email'          =>  'required|string|email|max:255|unique:company',
    //         'office_phone'          =>  'required|string|max:20',
    //         'warehouse_address1'    =>  'required|string|max:255',
    //         'warehouse_address2'    =>  'string|max:255',
    //         'warehouse_lat'         =>  'required|integer|max:255',
    //         'warehouse_lng'         =>  'required|integer|max:255',
    //     ]);
    //     $admin_validator = Validator::make($admin_account, [
    //         'username'              =>  'required|string|min:5|max:20|unique:account',
    //         'password'              =>  'required|confirmed|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/', 
    //         'email'                 =>  'required|string|email|max:255|unique:account',
    //         'phone'                 =>  'required|string|max:20',
    //         'first_name'            =>  'string|max:255',
    //         'last_name'             =>  'string|max:255',
    //     ]);
    //     if($company_validator->fails()){
    //         return $this->sendError('Validation Error', $company_validator->errors());
    //     }
    //     if($admin_validator->fails()){
    //         return $this->sendError('Validation Error', $admin_validator->errors());
    //     }
    //     $company = Company::create($company_info);
    //     $admin_account['password'] = password_hash($admin_account['password'], PASSWORD_DEFAULT);
    //     $admin_account['type'] = 'admin';
    //     $admin_account['company_id'] = $company->id;
    //     $account = Account::create($admin_account);
    //     $success['token'] = $account->createToken('FYP')->accessToken;
    //     $success['account'] = $account->username;
    //     return $this->sendResponse($success, 'User Register Success!');
    // }

    public function login(Request $request){
        if(Auth::attempt(['username'   =>  $request->username, 'password'   =>  $request->password])){
            $account = $request->user();
            if(in_array($account->type, ['staff']) > 0){
                $account = Auth::user();
                $success['token'] = $account->createToken('FYP')->accessToken;
                $success['account'] = $account;
                return $this->sendResponse($success, 'User Login Success!');
            }
        }
        return $this->sendError('Unauthorised!', ['error'=>'Unauthorised!']);
    }

    public function getAllTasks(Request $request){
        if($user = request()->user()){
            $id = $user->id;
            $allTasks = Group::findRecord($id);
            // if(!$allTasks->isEmpty()){
            //     $allTask->route_order = json_decode($allTasks->route_order,true);
            // }
            return $this->sendResponse($allTasks, 'success');
        }
        return $this->sendError('Unauthorised!', ['error'=>'Unauthorised!']); 
    }

    public function getTask(Rrequest $request){
        return ;
    }
}
