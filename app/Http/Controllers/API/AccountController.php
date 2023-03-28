<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:5|max:20',
            'password' => 'required|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/',
        ]);

        if ($validator->failed()) {
            return $this->unauthorized();
        }

        $validated = $validator->validated();
        if (Auth::attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
            $account = Auth::user();
            if ($account->type === Account::TYPE_STAFF) {
                $success['token'] = $account->createToken('FYP')->accessToken;
                $success['account'] = $account;
                return $this->sendResponse($success, 'User Login Success!');
            }
        }

        return $this->unauthorized();
    }
}
