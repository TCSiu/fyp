<?php

namespace App\Http\Controllers;

use App\Models\Base\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

	public function list(Request $request, string $model = ''){
		if($className = Model::checkModel($model)){
			$page_title = $className::PAGE_TITLE;
			$inpage_title = 'View ' . $page_title;
			$target_fields = $className::TABLE_FIELDS;
			$allow_actions = $className::ALLOW_ACTIONS;
			$can_create = $className::CAN_CREATE;
			$data = $className::getData(20);
			$total_count = $className::getCount();
	
			return view('panel/list')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('target_fields', $target_fields)
				->with('allow_actions', $allow_actions)
				->with('can_create', $can_create)
				->with('data', $data)
				->with('total_count', $total_count);
		}
		throw new \Exception();
	}

	public function create(Request $request, string $model = ''){
		if ($className = Model::checkModel($model)) {
			$message = [];
			$page_title = $className::PAGE_TITLE;
			$inpage_title = 'Create ' . $page_title;
			return view('panel/create')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('msg', $message)
				->with('method', 'create');
		}
		throw new \Exception();
	}

	public function view(Request $request, string $model = '', int $id = 1){
		if($className = Model::checkModel($model)){
			$page_title = $className::PAGE_TITLE;
			$inpage_title = 'View ' . $page_title . ' ' . $id;
			$fields = $className::FIELDS;
			$data = $className::findRecord($id);
			return view('panel/view')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('fields', $fields)
				->with('data', $data)
				->with('id', $data->id);
		}
		throw new \Evception();
	}

	public function edit(Request $request, string $model = '', int $id = 1){
		if($className = Model::checkModel($model)){
			$record = $className::findRecord($id);
			if(isset($record) && $record instanceOf Model){
				$msg = [];
				$page_title = $className::PAGE_TITLE;
				$inpage_title = 'Edit ' . $page_title . ' ' . $id;
				return view('panel/create')
					->with('model', $model)
					->with('title', $page_title)
					->with('inpage_title', $inpage_title)
					->with('msg', $msg)
					->with('record', $record)
					->with('method', 'store')
					->with('id', $id);
			}
		}
		throw new \Evception();
	}

	public function store(Request $request, string $model = '', int $id = -1){
        if ($className = Model::checkModel($model)) {
            $temp = $request->all();
			if(isset($temp['items_is_remove'])){
				foreach($temp['items_is_remove'] as $key => $value){
					unset($temp['items_name'][$key]);
					unset($temp['items_number'][$key]);
				}
			}
			$validator = Validator::make($temp, $className::VALIDATE_RULES, $className::VALIDATE_MESSAGE);
			if ($validator->fails()) {
				$errors = $validator->errors()->toArray();
				$message['type'] = 'errors';
				foreach ($errors as $k => $row) {
					foreach ($row as $kk => $rrow) {
						$message['message'][] = $rrow;
					}
				}
				return redirect()
				->back()
				->with('msg', $message)
				->withInput();
			}
			$data = $className::matchField($temp);
			$order = $className::updateOrCreate(['id' => $id], $data);
			return redirect(route('cms.view', ['model' => $model, 'id' => $order->id]));
        }
		throw new \Evception();
	}

	public function delete(Request $request, string $model = '', int $id = -1){
		if($className = Model::checkModel($model)){
			$record = $className::findRecord($id);
			if(isset($record) && $record instanceOf Model){
				$record->deleteRecord();
				return redirect(route('cms.list', ['model' => $model]));
			}
		}
		throw new \Evception();
	}
}