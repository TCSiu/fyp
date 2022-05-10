<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Base\Model;

class PanelController extends Controller
{
    public function index(Request $request){
        if($request->hasCookie('auth_user')){
            $auth_user = $request->cookie('auth_user');
            $data = json_decode($auth_user, true);
            if($data['is_admin']){
                return view('panel/panel')->with('title', 'Panel Page');
            }
        }
        return view('login')->with('errors', 'Unauthorized user');
    }

    public function list(Request $request, string $model = '', int $page = 1){
        $className = Model::checkModel($model);
        $page_title = $className::getPageTitle();
        $inpage_title = $className::getInpageTitle();
        $data = $className::getData();
        $target_field = $className::getTargetField();

        return view('panel/listing')
            ->with('title', $page_title)
            ->with('inpage_title', $inpage_title)
            ->with('data', $data)
            ->with('count', 0)
            ->with('total_count', 0);
    }
}