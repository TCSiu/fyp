<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index(Request $request){

        $auth_user = $request->cookie('auth_user');
        $data = json_decode($auth_user, true);

        if($data['is_admin']){
            return view('panel')->with('title', 'Panel Page');
        }
        return view('login')->with('errors', 'Unauthorized user');
    }
}