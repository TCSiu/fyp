<?php

namespace App\Models;

use App\Models\AccessToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Base\Model;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'is_admin',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function checkTokenExpiry(string $purpose = ''){
		$access_token = AccessToken::getByUserID($this->id, $purpose);
		if (isset($access_token) && $access_token instanceof AccessToken) {
			if ($access_token->isExpired()) {
				return $this->renewToken($purpose);
			}
			return $access_token;
		}
		return false;
	}

	public function renewToken(string $purpose = ''){
		return AccessToken::renewToken($this->id, $purpose);
	}
}
