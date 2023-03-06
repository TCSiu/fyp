<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Support\Str;
use Validator;

class Group extends Model
{
	use HasFactory;

	protected $table = 'order_group';

	public const PAGE_TITLE 	= 'Route';
	public const OPERATION		= ['route_planning'];	

	protected $fillable = [
		 'company_id',
		 'route_order',
		 'relative_staff',
		 'status',
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
		return static::select(['id', 'uuid', 'status', 'updated_at'])->where('id', $id)->first();
	}

	public static function findRecordByStaffId(int $id = -1){
		return static::select(['id', 'uuid', 'status', 'updated_at'])->where([['relative_staff', '=', $id],['status', '!=', 'finished']])->get();
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

	// public static function getCsvData(){
	// 	return Order::select('id', 'lat', 'lng', 'deliver1', 'deliver2')->where('is_delete', 0)->where('is_in_group', 0)->where('is_complete', 0)->get();
	// }
	
}
