<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class Staff extends Model
{
	use HasFactory;

	protected $table = 'staff';

	public const PAGE_TITLE = 'Staff Account';
	public const CAN_CREATE = true;
    public const TABLE_FIELDS 		= ['id', 'ac_name', 'is_locked', 'is_active'];

    public const ALLOW_ACTIONS 		= ['view', 'edit', 'delete'];
	public const VALIDATE_RULES 	= [
		'ac_name' 			    =>  'required|string|min:5|max:20|unique:staff',
		'password' 		        =>  'required|confirmed|Password::min(8)->mixedCase()->numbers()',
	];

	protected $fillable = [
         'ac_name',
         'password',
         'login_failed_count',
         'is_locked',
         'is_active',
         'is_delete',
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
	
}
