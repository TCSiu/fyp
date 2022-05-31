<?php

namespace App\Http\Controllers;

use App\Models\Base\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

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

	public function list(string $model = ''){
		if($className = Model::checkModel($model)){
			$page_title = $className::PAGE_TITLE;
			$inpage_title = 'View ' . $page_title;
			$target_fields = $className::TABLE_FIELDS;
			$allow_actions = $className::ALLOW_ACTIONS;
			$operations = $className::OPERATION;
			$data = $className::getData(20);
			$total_count = $className::getCount();
	
			return view('panel/list')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('target_fields', $target_fields)
				->with('allow_actions', $allow_actions)
				->with('operations', $operations)
				->with('data', $data)
				->with('total_count', $total_count);
		}
		throw new \Exception();
	}

	public function create(string $model = ''){
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

	public function view(string $model = '', int $id = 1){
		if($className = Model::checkModel($model)){
			$page_title = $className::PAGE_TITLE;
			$inpage_title = 'View ' . $page_title . ' ' . $id;
			$fields = $className::VIWES_FIELDS;
			$data = $className::findRecord($id);
			return view('panel/view')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('fields', $fields)
				->with('data', $data)
				->with('id', $data->id);
		}
		throw new \Exception();
	}

	public function edit(string $model = '', int $id = 1){
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
		throw new \Exception();
	}

	public function store(Request $request, string $model = '', int $id = -1){
		if ($className = Model::checkModel($model)) {
			$temp = $request->all();
			$temp = $className::modifyData($temp);
			$validator = Validator::make($temp, $className::getValidateRules($id), $className::VALIDATE_MESSAGE);
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
			$data 	= $className::matchField($temp);
			$order 	= $className::updateOrCreate(['id' => $id], $data);
			return redirect(route('cms.view', ['model' => $model, 'id' => $order->id]));
		}
		throw new \Exception();
	}

	public function delete(string $model = '', int $id = -1){
		if($className = Model::checkModel($model)){
			$record = $className::findRecord($id);
			if(isset($record) && $record instanceOf Model){
				$record->deleteRecord();
				return redirect(route('cms.list', ['model' => $model]));
			}
		}
		throw new \Exception();
	}

	public function get_csv(string $model = ''){
		if($className = Model::checkModel($model)){
			$orders	=	$className::getCsvData();
			$path = storage_path('app\\public\\order\\');
			$filename = 'order_to_group.csv';
			$columns = ['#', 'lat', 'lng', 'Location'];

			if(!Storage::exists($path)){
				Storage::makeDirectory($path, 0777, true, true);
			}
			$file = fopen($path.$filename, 'w');
			fputcsv($file, $columns);
			$data = [
				'#'			=>	$orders->id,
				'lat'		=>	$orders->lat,
				'lng'		=>	$orders->lng,
				'location'	=>	$orders->location,
			];
			fputcsv($file, $data);
			fclose($file);
		}
		throw new \Exception();
	}
}