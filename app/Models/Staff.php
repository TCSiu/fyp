<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class Staff extends Model
{
	use HasFactory;

	protected $table = 'staff';

	public const PAGE_TITLE = 'Staff Account';
	public const CAN_CREATE = true;
    public const TABLE_FIELDS 		= ['id', 'first_name', 'last_name', 'ac_name', 'is_locked', 'is_active'];

    public const ALLOW_ACTIONS 		= ['view', 'edit', 'delete'];

	public const VALIDATE_MESSAGE 	= [
		'password.regex'		=>	'The password does not match the minimum requirment!'
	];

	public const VIWES_FIELDS = [
		'sex'					=>	'normal',
		'first_name'			=>	'normal',
		'last_name'				=>	'normal',
		'phone_number'			=>	'normal',
		'email'					=>	'normal',
		'ac_name'				=>	'normal',
		'password'				=>	'none',
		'login_failed_count'	=>	'normal',
		'is_locked'				=>	'boolean',
		'is_active'				=>	'boolean',
		'is_delete'				=>	'none',
	];

	protected $fillable = [
		'sex',
		'company_id',
		'first_name',
		'last_name',
		'phone_number',
		'email',
        'ac_name',
        'password',
        'login_failed_count',
        'is_locked',
        'is_active',
        'is_delete',
		'api_token',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		 'password',
	];

	public static function getTitle(){
		return static::PAGE_TITLE;
	}

	public static function getCount(){
		return static::where('is_delete', 0)->count();
	}

	public static function getValidateRules(int $id = -1){
		return [
			'sex' 					=> 	'required',
			'first_name' 			=> 	'required|string|max:255',
			'last_name' 			=> 	'required|string|max:255',
			'phone_number' 			=> 	'required|Regex:/^(\+\d{1,3})?([.\s-]?)(\d){4}([.\s-]?)(\d){4}$/',
			'email'					=>	'required|email',
			'ac_name' 			    =>  'required|string|min:5|max:20|unique:staff,ac_name,' . $id,
			'password' 		        =>  'required|confirmed|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/',
		];
	}

	public static function getData(int $paginate_size = -1){
		if($paginate_size > 0){
			return static::where('is_delete', 0)->paginate($paginate_size);
		}
		return static::where('is_delete', 0)->get();
	}

	public static function matchField($auth_user, $data){
		$temp = [];
		if(empty(static::VIWES_FIELDS)){
			return $data;
		}
		foreach($data as $key => $value){
			if(array_key_exists($key, static::VIWES_FIELDS)){
				$temp[$key] = $value;
			}
		}
		if(isset($data['password'])){
			$temp['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		}
		$temp['company_id'] 		= intval($auth_user['id']);
		$temp['api_token']			= Hash::make(date('YmdHisu'));
		$temp['login_failed_count'] = 0;
		$temp['is_locked'] 			= 0;
		$temp['is_active'] 			= 1;
		$temp['is_delete'] 			= 0;
		return $temp;
	}

	public function deleteRecord(){
        $this->is_delete = true;
        $this->save();
    }
}
