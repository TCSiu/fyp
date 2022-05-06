<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
	use HasFactory;

	protected $table = 'access_token';

	protected $fillable = [
	   'user_id',
	   'access_token',
	   'expiry_date',
	   'purpose',
	   'is_active',
	];

	protected $hidden = [
		'access_token',
	];

	public static function createToken(int $user_id = 0, string $purpose = ''){
		return AccessToken::create([
			'user_id'	   	=> $user_id, 
			'access_token'  => Hash::make(date('YmdHisu')), 
			'expiry_date'   => date('Y-m-d H:i:s', strtotime('+1 hours')),
			'purpose'		=> $purpose,
			'is_active'		=> 1
		]);
	}

	public static function revokeToken(int $user_id = 0, string $purpose = ''){
		return AccessToken::where('user_id', $user_id)
		->where('purpose', $purpose)
		->update(['is_active' => 0]);
	}

	public function revokeSimilar(){
		return static::revokeToken($this->user_id, $this->purpose);
	}

	public static function renewToken(int $user_id = 0, string $purpose = ''){
		static::revokeToken($user_id, $purpose);
		return static::createToken($user_id, $purpose);
	}

	public function renewSimilar(){
		return static::renewToken($this->user_id, $this->purpose);
	}


	public static function getByUserID(int $user_id = 0, string $purpose = ''){
		if(isset($user_id) && is_numeric($user_id) && isset($purpose) && is_string($purpose)){
			$user_id = intval($user_id);
			$purpose = trim($purpose);
			if($user_id > 0 && !empty($purpose)){
				return AccessToken::where('is_active', 1)->where('purpose', $purpose)->where('user_id', $user_id)->orderByDesc('created_at')->first();
			}
		}
		return false;
	}
	
	public function isExpired(){
		return strtotime($this->expiry_date) < strtotime('now');
	}

}
