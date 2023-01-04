<?php

namespace App\Http\Controllers;

use App\Models\Base\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\Staff;

class ApiController extends Controller
{

	public function login(Request $request){
        $ac_name = $request->ac_name;
        $password = $request->password;
        $staff = Staff::where('ac_name', $ac_name)->first();
        $error = 'Unknown username. Please enter again.';
        if(isset($staff) && $staff instanceof Staff){
            $error = 'Password cannot be empty. Please enter again.';
            if(!is_null($password) && is_string($password)){
                if(password_verify($password, $staff->password)){
                    return response(['message' => 'login success', 'status' => 1, 'api_token' => $staff['api_token'], 'staff' => $staff]);
                }
                $error = 'Wrong password. Please enter again.';
            }
        }
        return response(['message' => $error, 'status' => 0]);
    }

    public function index(Request $request){
        $auth_staff = $request->auth_staff;
        $company_id = $auth_staff->company_id;
        $list = Group::where('compnay_id', $company_id);
        return response(['status' => 1, 'company_id' => $company_id]);
    }
    
}