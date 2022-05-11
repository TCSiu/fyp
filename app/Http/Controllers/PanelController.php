<?php

namespace App\Http\Controllers;

use App\Models\Base\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
        $target_field = $className::getTargetField();
        $data = $className::getData();
        $total_count = $data->count();
        $data = $data->paginate(20);

        return view('panel/listing')
            ->with('model', $model)
            ->with('title', $page_title)
            ->with('inpage_title', $inpage_title)
            ->with('data', $data)
            ->with('total_count', $total_count);
    }

    public function create(Request $request, string $model = ''){
        $data = [];
        $className = Model::checkModel($model);
        $data['sex'] = 'Male';
        $data['first_name'] = 'test';
        $data['last_name'] = 'admin';
        $data['phone_number'] = '12345678';
        $data['address'] = 'Tin Shui Wai';
        $data['delivery_date'] = '2022-11-05';
        $data['product_name_and_number'] = json_encode(['test' => 1, 'test2' => 2]);
        $data['is_in_group'] = 0;
        $data['is_complete'] = 0;
        $data['is_delete'] = 0;
        $order = $className::create($data);
    }
}