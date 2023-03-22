<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Support\Str;
use Validator;

class Task extends Model
{
	use HasFactory;

	protected $table = 'task';

	public const PAGE_TITLE 		= 'Task';
	public const OPERATION			= ['route_planning'];
	public const ALLOW_ACTIONS 		= ['view', 'assign'];	
	public const TABLE_FIELDS 		= ['id' => 'id', 'status' => 'status'];

	protected $fillable = [
		 'company_id',
		 'route_order',
		 'relative_staff',
		 'status',
	];

	public const VIWES_FIELDS = [
		'status'				=>	'normal',
		'relative_staff'		=> 	'table_json.first_name/last_name/email/phone',
		'route_order'			=>	'table.first_name/last_name/phone_number/delivery1/delivery2/status',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	// protected $hidden = [
	//	 'address',
	// ];

	protected static function boot(){
    	parent::boot();
		static::creating(function ($model) {
			if(!(isset($model->uuid) && is_string($model->uuid) && strlen($model->uuid) > 0)){
				$model->uuid = Str::uuid()->toString();
			}
		});
	}

	public static function getTitle(){
		return static::PAGE_TITLE;
	}

	public static function findRecord(int $id = -1){
		$staff_info = [];
		if(($orderStatusClass = Model::checkModel('order status')) && ($taskOrderClass = Model::checkModel('task order')) && ($account = Model::checkModel('account'))){
			$data = static::where('id', $id)->first();
			$taskOrder = $taskOrderClass::getOrdersByTaskUuid($data->uuid)->toArray();
			$orderStatus = $orderStatusClass::getBatchStatusByUuid($taskOrder);
			$route_order = json_decode($data->route_order, true);
			forEach($route_order as $key => $value){
				$target_uuid = $value['uuid'];
				$value['status'] = $orderStatus[$target_uuid]['status'];
				$route_order[$key] = $value;
			}
			if(isset($data->relative_staff)){
				$relative_staff = $account::findRecord($data->relative_staff);
				if(isset($relative_staff)){
					$staff_info['first_name'] = $relative_staff->first_name;
					$staff_info['last_name'] = $relative_staff->last_name;
					$staff_info['email'] = $relative_staff->email;
					$staff_info['phone'] = $relative_staff->phone;
				}
			}
			
			$data['route_order'] = $route_order;
			$data['relative_staff'] = $staff_info;
			return $data;
		}
		throw new \Exception();
	}

	public static function findRecordByStaffId(int $staff_id = -1){
		return static::select(['id', 'uuid', 'status', 'updated_at'])->where([['relative_staff', '=', $staff_id],['status', '!=', 'finished']])->get();
	}

	public static function findRecordByUuid(String $uuid = ''){
		return static::where('uuid', $uuid)->first();
	}

	public static function getRouteUuid(String $uuid = ''){
		$routes_uuid = [];
		$task = static::where('uuid', $uuid)->first();
		$routes = json_decode($task->route_order, true);
		foreach($routes as $row){
			array_push($routes_uuid, $row['uuid']);
		}
		return $routes_uuid;
	}

	public static function initOrder(array $input = []){
		$request = [];
		$uuid_list = [];
		$rules = [
			'uuid'		=>	'required|array',
			'uuid.*'	=>	'exists:order,uuid',
		];
		foreach($input as $row){
			array_push($uuid_list, $row['uuid']);
		}
		$request['uuid'] = $uuid_list;
		$validator = Validator::make($request, $rules);
		if($validator->fails()){
			return false;
		}
		return json_encode($input);
	}

	public static function getOrderUuid(array $input = []){
		$uuid_list = [];
		foreach($input as $row){
			array_push($uuid_list, $row['uuid']);
		}
		return $uuid_list;
	}

	public static function updateStatus(String $uuid = ''){
		$status_list = ['preparing', 'delivering', 'finished'];
		$routes = TaskOrder::getOrdersByTaskUuid($uuid)->toArray();
		$status_count = OrderStatus::countStatus($routes);
		if(isset($status_count) & sizeOf($status_count) > 0){
			if(sizeOf($status_count) == 1){
				for($i = 0; $i < sizeOf($status_list); $i++){
					if(array_key_exists($status_list[$i], $status_count)){
						return static::where('uuid', $uuid)->update(['status' => $status_list[$i]]);
						break;
					}
				}
			}
		}
		return false;
	}

	public static function assignTask(int $order_id = 0, int $staff_id = 0){
		return static::where('id', $order_id)->update(['relative_staff' => $staff_id]);
	}

	// public static function getCsvData(){
	// 	return Order::select('id', 'lat', 'lng', 'deliver1', 'deliver2')->where('is_delete', 0)->where('is_in_group', 0)->where('is_complete', 0)->get();
	// }
	
}
