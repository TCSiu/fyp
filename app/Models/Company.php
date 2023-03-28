<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    public const PAGE_TITLE = 'Company';

    public const VIWES_FIELDS = [
        'company_name'				=> 'normal',
        'office_address'			=> 'normal',
        'office_email' 				=> 'normal',
        'office_phone' 				=> 'normal',
        'warehouse_address1'    	=> 'special.warehouse_address',
        'warehouse_address2' 		=> 'special.Apartment,_unit,_suite,_or_floor_#',
        'lat'						=> 'none',
        'lng'						=> 'none',
        'created_at' 				=> 'none',
        'updated_at' 				=> 'none',
    ];

    protected $fillable = [
         'company_name',
         'office_address',
         'office_email',
         'office_phone',
         'warehouse_address1',
         'warehouse_address2',
         'lat',
         'lng',
    ];

    public static function getInpageTitle(int $id = -1)
    {
        $model = static::findRecord($id);
        if ($model instanceof Company) {
            return trim($model->company_name);
        }
        return sprintf('%s #%d', self::PAGE_TITLE, $id);
    }
}
