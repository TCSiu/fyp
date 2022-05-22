<?php
declare(strict_types=1);
namespace App\Commons;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Commons\Constants;
use App\Models\Base\Model;

class Utility
{
    public static function required()
	{
		return '<span class="text-danger required-mark ms-1">*</span>';
	}
}