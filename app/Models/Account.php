<?php

namespace App\Models;

// use App\Models\AccessToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Base\Model;

class Account extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'account';

	public const PAGE_TITLE 		= 'Account';
	public const CAN_CREATE 		= true;
    public const TABLE_FIELDS 		= ['first_name', 'last_name', 'is_locked', 'is_active'];
	public const ALLOW_ACTIONS 		= ['view', 'edit', 'delete'];
	public const OPERATION	 		= ['create'];
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	public const VIWES_FIELDS = [
		'username'					=> 	'normal',
		'first_name' 				=> 	'normal',
		'last_name' 				=> 	'normal',
		'email'		 				=> 	'normal',
		'phone' 					=> 	'normal',
		'type'	 					=> 	'normal',
		'login_failed_count'		=> 	'normal',
		'is_locked'				 	=> 	'boolean',
		'is_active' 				=> 	'boolean',
	];

	protected $fillable = [
		'username',
		'password',
		'first_name',
		'last_name',
		'email',
		'phone',
		'type',
		'company_id',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
	];

	public static function getValidateRules(int $id = -1){
		return [
			'username'              =>  'string|min:5|max:20|unique:account,username,' . $id,
			'password'              =>  'nullable|confirmed|Regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)[A-Za-z\d!@#$%^\&*()_+-={}\[\]:\";\'<>,\.\\?~`]{8,}$/', 
			'email'                 =>  'string|email|max:255|unique:account,email,' . $id,
			'phone'                 =>  'string|max:20',
			'first_name'            =>  'string|max:255',
			'last_name'             =>  'string|max:255',
		];
	}

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	// protected $casts = [
	// 	'email_verified_at' => 'datetime',
	// ];

	public static function getInpageTitle(int $id = -1){
		return static::findRecord($id)->username;
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
			if(array_key_exists($key, ['password'])){
				$temp[$key] = password_hash($value, PASSWORD_DEFAULT);
			}
		}
		return $temp;
	}

	public static function getData(int $paginate_size = -1, int $company_id = 0){
		if($paginate_size > 0){
			return static::where(['company_id' => $company_id, 'is_delete'=> 0])->paginate($paginate_size);
		}
		return static::where('is_delete', 0)->get();
	}

	public static function getCount(){
		return static::where('is_delete', 0)->count();
	}
}
