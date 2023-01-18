<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class Group extends Model
{
	use HasFactory;

	protected $table = 'order_group';

	public const PAGE_TITLE 	= 'Order Group';
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

	public static function getTitle(){
		return static::PAGE_TITLE;
	}

	// public static function getCsvData(){
	// 	return Order::select('id', 'lat', 'lng', 'deliver1', 'deliver2')->where('is_delete', 0)->where('is_in_group', 0)->where('is_complete', 0)->get();
	// }
	
}
