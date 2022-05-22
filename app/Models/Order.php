<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class Order extends Model
{
	use HasFactory;

	protected $table = 'order';

	public const PAGE_TITLE 		= 'Order';
	public const CAN_CREATE = true;
	public const TABLE_FIELDS 		= ['id', 'delivery_date', 'deliver1', 'is_in_group', 'is_complete'];
	public const ALLOW_ACTIONS 		= ['view', 'edit', 'delete'];

	public const VALIDATE_MESSAGE 	= [
		'items_name.required'		=>	'Product table cannot be empty.',
		'items_name.*.required'		=>	'Product name :index cannot be empty.',
		'items_number.*.required'	=>	'Product number :index field cannot be empty.',
	];

	public const VIWES_FIELDS = [
		'sex' => 'normal',
		'first_name' => 'normal',
		'last_name' => 'normal',
		'phone_number' => 'normal',
		'deliver1' => 'normal',
		'deliver2' => 'normal',
		'delivery_date' => 'normal',
		'product_name_and_number' => 'table',
		'is_in_group' => 'none',
		'is_complete' => 'boolean',
		'is_delete' => 'none',
	];

	protected $fillable = [
		'sex',
		'first_name',
		'last_name',
		'phone_number',
		'deliver1',
		'deliver2',
		'lat',
		'lng',
		'delivery_date',
		'product_name_and_number',
		'is_in_group',
		'is_complete',
		'is_delete',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'deliver1',
		'deliver2',
		'phone_number',
	];

	public static function getCount(){
		return static::where('is_delete', 0)->count();
	}

	public static function getValidateRules(int $id = -1){
		return [
			'sex' 				=> 'required',
			'first_name' 		=> 'required|string|max:255',
			'last_name' 		=> 'required|string|max:255',
			'phone_number' 		=> 'required|Regex:/^(\+\d{1,3})?([.\s-]?)(\d){4}([.\s-]?)(\d){4}$/',
			'deliver1' 			=> 'required|string',
			'deliver2' 			=> 'string',
			'delivery_date' 	=> 'required|date_format:Y-m-d|after_or_equal:today',
			'items_name' 		=> 'required|array',
			'items_name.*' 		=> 'required|string',
			'items_number.*' 	=> 'required|integer',
		];
	}

	public static function getData(int $paginate_size = -1){
		if($paginate_size > 0){
			return static::where('is_delete', 0)->paginate($paginate_size);
		}
		return static::where('is_delete', 0)->get();
	}

	public static function matchField($data){
		$temp = [];
		if(empty(static::VIWES_FIELDS)){
			return $data;
		}
		foreach($data as $key => $value){
			if(array_key_exists($key, static::VIWES_FIELDS)){
				$temp[$key] = $value;
			}
		}
		$product_name_and_number = [];
		if(isset($data['items_name'], $data['items_number'])){
			if((is_array($data['items_name']) && is_array($data['items_number'])) && (sizeOf($data['items_name']) > 0 && sizeOf($data['items_number']) > 0)){
				$count = 1;
				foreach(array_combine($data['items_name'], $data['items_number']) as $name => $number){
					$product_name_and_number[$count++] = [
						'product_name' 		=> $name,
						'product_number' 	=> $number,
					];
				}
			}
		}
		$temp['product_name_and_number'] 	= json_encode($product_name_and_number, JSON_FORCE_OBJECT);
		$temp['is_in_group'] 				= 0;
		$temp['is_complete'] 				= 0;
		$temp['is_delete'] 					= 0;
		return $temp;
	}

	public function deleteRecord(){
        $this->is_delete = true;
        $this->save();
    }

	public static function checkExistingTable(array $data = []){
		if(isset($data['items_is_remove'])){
			foreach($data['items_is_remove'] as $key => $value){
				unset($data['items_name'][$key]);
				unset($data['items_number'][$key]);
			}
		}
		return $data;
	}

	public static function modifyData(array $data = []){
		if(isset($data)){
			if(method_exists(static::MODEL_NAMESPACE . 'Order', 'checkExistingTable')){
				$data = static::checkExistingTable($data);
			}
		}
		return $data;
	}
}
