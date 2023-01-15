<?php

namespace App\Http\Controllers;

use App\Models\Base\Model;
use App\Models\ImageUsage;
use App\Http\Requests\WebRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PanelController extends Controller
{
	public function index(WebRequest $request){
		$account			=	Auth::user();
		$account_id			=	$account->id;
		$sidebar_image		= 	ImageUsage::getImages('\App\Models\Account', $account_id);
		// return dd($sidebar_image);

		return view('panel/panel')
			->with('title', 'Panel Page')
			->with('sidebar_image', $sidebar_image);
	}

	public function list(WebRequest $request, string $model = ''){
		if($className = Model::checkModel($model)){
			$page_title 		= 	$className::PAGE_TITLE;
			$inpage_title 		= 	'View ' . $page_title;
			$target_fields 		= 	$className::TABLE_FIELDS;
			$allow_actions 		= 	$className::ALLOW_ACTIONS;
			$account			=	Auth::user();
			$account_id			=	$account->id;
			$company_id			=	$account->company_id;	
			$operations 		= 	$className::OPERATION;
			$data 				= 	$className::getData(20, $company_id);
			$total_count 		= 	$className::getCount();
			$sidebar_image		=	ImageUsage::getImages('\App\Models\Account', $account_id);
			$images 			= 	ImageUsage::getImages($className, $account_id);

			return view('panel/list')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('target_fields', $target_fields)
				->with('allow_actions', $allow_actions)
				->with('operations', $operations)
				->with('data', $data)
				->with('total_count', $total_count)
				->with('sidebar_image', $sidebar_image)
				->with('images', $images);
		}
		throw new \Exception();
	}

	public function create(WebRequest $request, string $model = ''){
		if ($className = Model::checkModel($model)) {
			$message 			= 	[];
			$page_title 		= 	$className::PAGE_TITLE;
			$inpage_title 		= 	'Create ' . $page_title;
			$isCreate 			= 	true;
			$account			=	Auth::user();
			$account_id			=	$account->id;
			$sidebar_image		=	ImageUsage::getImages('\App\Models\Account', $account_id);

			return view('panel/create')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('msg', $message)
				->with('isCreate', $isCreate)
				->with('sidebar_image', $sidebar_image);
		}
		throw new \Exception();
	}

	public function view(WebRequest $request, string $model = '', int $id = -1){
		if($className = Model::checkModel($model)){
			$page_title 		= 	$className::PAGE_TITLE;
			$inpage_title 		= 	'View ' . $className::getInpageTitle($id);
			$fields 			= 	$className::VIWES_FIELDS;
			$data 				= 	$className::findRecord($id);
			$account			=	Auth::user();
			$account_id			=	$account->id;
			$sidebar_image		=	ImageUsage::getImages('\App\Models\Account', $account_id);
			$images 			= 	ImageUsage::getImages($className, $id);

			return view('panel/view')
				->with('model', $model)
				->with('title', $page_title)
				->with('inpage_title', $inpage_title)
				->with('fields', $fields)
				->with('data', $data)
				->with('id', $data->id)
				->with('sidebar_image', $sidebar_image)
				->with('images', $images);
		}
		throw new \Exception();
	}

	public function edit(WebRequest $request, string $model = '', int $id = 1){
		if($className = Model::checkModel($model)){
			$record = $className::findRecord($id);
			if(isset($record) && $record instanceOf Model){
				$msg 			= 	[];
				$page_title 	= 	$className::PAGE_TITLE;
				$inpage_title 	= 	'Edit ' . $page_title . ' ' . $id;
				$account		=	Auth::user();
				$account_id		=	$account->id;
				$sidebar_image	=	ImageUsage::getImages('\App\Models\Account', $account_id);
				$images 		= 	ImageUsage::getImages($className, $id);
				// return dd($images);

				return view('panel/create')
					->with('model', $model)
					->with('title', $page_title)
					->with('inpage_title', $inpage_title)
					->with('msg', $msg)
					->with('record', $record)
					->with('method', 'store')
					->with('id', $id)
					->with('sidebar_image', $sidebar_image)
					->with('images', $images);
			}
		}
		throw new \Exception();
	}

	public function store(WebRequest $request, string $model = '', int $id = -1){
		if ($className = Model::checkModel($model)) {
			$user 				= Auth::user();
			$temp 				= $request->all();
			$temp 				= $className::modifyData($temp);
			$validator 			= Validator::make($temp, $className::getValidateRules($id), $className::VALIDATE_MESSAGE);

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
			$data 				= $className::matchField($user, $temp);
			// return dd($data);
			$record 			= $className::updateOrCreate(['id' => $id], $data);
			$imageUsage 		= ImageUsage::fileUsageStore($className, $record->id, $temp);
			return redirect(route('cms.view', ['model' => $model, 'id' => $record->id]));
		}
		throw new \Exception();
	}

	public function delete(WebRequest $request, string $model = '', int $id = -1){
		if($className = Model::checkModel($model)){
			$record 			= $className::findRecord($id);

			if(isset($record) && $record instanceOf Model){
				$record->deleteRecord();
				return redirect(route('cms.list', ['model' => $model]));
			}
		}
		throw new \Exception();
	}

	public function get_csv(WebRequest $request, string $model = ''){
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

	public function test(){
        return view('panel/test')->with('title', 'Panel Page');
	}

	// public function testPy(){
	// 	$url = Storage::disk('csv')->url('sample_data.csv');
	// 	$process = new Process(['python', 'CVRP.py', $url]);
    //     $process->run();

    //     // error handling
    //     if (!$process->isSuccessful()) {
    //         throw new ProcessFailedException($process);
    //     }

    //     $output_data = $process->getOutput();
    //     return view('panel/test')->with('title', 'Panel Page')->with('output', $output_data);
    // }
}