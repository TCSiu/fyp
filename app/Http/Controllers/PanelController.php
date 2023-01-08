<?php

namespace App\Http\Controllers;

use App\Models\Base\Model;
use App\Http\Requests\WebRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PanelController extends Controller
{
	public function index(WebRequest $request){
		return view('panel/panel')->with('title', 'Panel Page');
	}

	public function list(string $model = ''){
		if($className = Model::checkModel($model)){
			$page_title 		= 	$className::PAGE_TITLE;
			$inpage_title 		= 	'View ' . $page_title;
			$target_fields 		= 	$className::TABLE_FIELDS;
			$allow_actions 		= 	$className::ALLOW_ACTIONS;
			$company_id			=	Auth::user()->company_id;	
			$operations 		= 	$className::OPERATION;
			$data 				= 	$className::getData(20, $company_id);
			$total_count 		= 	$className::getCount();

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
			$inpage_title = 'View ' . $className::getInpageTitle($id);
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

	public function store(WebRequest $request, string $model = '', int $id = -1){
		if ($className = Model::checkModel($model)) {
			$user = Auth::user();
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
			$data 	= $className::matchField($user, $temp);
			$item 	= $className::updateOrCreate(['id' => $id], $data);
			return redirect(route('cms.view', ['model' => $model, 'id' => $item->id]));
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
			$path = 'order\\';
			$storage_path = storage_path('app\\'.$path);
			$filename = 'order_to_group.csv';
			$columns = ['#', 'lat', 'lng', 'Location'];

			if(!Storage::exists($path)){
				Storage::makeDirectory($path, 0664, true, true);
			}
			
			$file = fopen($storage_path.$filename, 'w');
			fputcsv($file, $columns);
			
			foreach($orders as $order){
				$data = [
					'#'			=>	$order->id,
					'lat'		=>	$order->lat,
					'lng'		=>	$order->lng,
					'location'	=>	trim($order->deliver1 . ' ' . $order->deliver2),
				];
				fputcsv($file, $data);
			}
			fclose($file);
			return redirect()->back();
		}
		throw new \Exception();
	}

	public function image(){
		return view('panel/image')->with('title', 'Panel Page');
	}
}