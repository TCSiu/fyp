<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Base\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    public const PAGE_TITLE 		= 'Order';
    public const OPERATION	 		= ['create', 'import_csv'];
    public const TABLE_FIELDS 		= ['uuid' => 'uuid', 'delivery1' => 'delivery_address', 'delivery2' => 'Apartment,_unit,_suite,_or_floor_#', 'is_in_group' => 'is_in_group'];
    public const ALLOW_ACTIONS 		= ['view', 'edit', 'delete'];

    public const VALIDATE_MESSAGE 	= [
        'items_name.lte'			=>	'All the items need to have a item number.',
        'items_number.lte'			=>	'All the items need to have a item number.',
        'items_name.required'		=>	'Product table cannot be empty.',
        'items_name.*.required'		=>	'Product name :index cannot be empty.',
        'items_number.*.required'	=>	'Product number :index field cannot be empty.',
    ];

    public const VIWES_FIELDS = [
        'sex' 						=> 	'normal',
        'first_name' 				=> 	'normal',
        'last_name' 				=> 	'normal',
        'phone_number' 				=> 	'normal',
        'delivery1' 				=> 	'special.delivery_address',
        'delivery2' 				=> 	'special.Apartment,_unit,_suite,_or_floor_#',
        'lat'						=>	'none',
        'lng'						=>	'none',
        'delivery_date' 			=> 	'normal',
        'product_name_and_number' 	=> 	'table',
        'is_in_group' 				=> 	'boolean',
        'status'					=>	'normal',
        'is_delete' 				=>	'none',
    ];

    protected $fillable = [
        'sex',
        'company_id',
        'first_name',
        'last_name',
        'phone_number',
        'delivery1',
        'delivery2',
        'lat',
        'lng',
        'delivery_date',
        'product_name_and_number',
        'is_in_group',
        'is_delete',
    ];

    protected $hidden = [
        'delivery1',
        'delivery2',
        'phone_number',
    ];

    public static function getCount(int $company_id = -1)
    {
        return static::where('company_id', $company_id)->where('is_delete', 0)->count();
    }

    public static function getValidateRules(int $id = -1)
    {
        return [
            'sex' 				=> 'required',
            'first_name' 		=> 'required|string|max:255',
            'last_name' 		=> 'required|string|max:255',
            'phone_number' 		=> 'required|Regex:/^(\+\d{1,3})?([.\s-]?)(\d){4}([.\s-]?)(\d){4}$/',
            'delivery1' 		=> 'required|string',
            'delivery2' 		=> 'nullable|string',
            'lat'				=> 'required|numeric',
            'lng'				=> 'required|numeric',
            'items_name' 		=> 'required|array|lte:items_number',
            'items_number' 		=> 'required|array|lte:items_name',
            'items_name.*' 		=> 'required|string',
            'items_number.*' 	=> 'required|integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!(isset($model->uuid) && is_string($model->uuid) && strlen($model->uuid) > 0)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public static function getData(int $paginate_size = -1, int $company_id = 0)
    {
        if ($paginate_size > 0) {
            return static::where(['is_delete' => 0, 'company_id' => $company_id])->paginate($paginate_size);
        }
        return static::where('is_delete', 0)->get();
    }

    public static function matchField($user = null, array $data = [])
    {
        $temp = [];
        if (empty(static::VIWES_FIELDS)) {
            return $data;
        }
        foreach ($data as $key => $value) {
            if (array_key_exists($key, static::VIWES_FIELDS)) {
                $temp[$key] = $value;
            }
        }
        $product_name_and_number = [];
        if (isset($data['items_name']) && isset($data['items_number'])) {
            if ((is_array($data['items_name']) && is_array($data['items_number'])) && (sizeof($data['items_name']) > 0 && sizeof($data['items_number']) > 0)) {
                $count = 1;
                foreach (array_combine($data['items_name'], $data['items_number']) as $name => $number) {
                    $product_name_and_number[$count++] = [
                        'product_name' 		=> $name,
                        'product_number' 	=> $number,
                    ];
                }
            }
        }
        $temp['company_id']					= intval($user->company_id);
        $temp['product_name_and_number'] 	= json_encode($product_name_and_number, JSON_FORCE_OBJECT);
        $temp['is_in_group'] 				= 0;
        $temp['is_delete'] 					= 0;
        return $temp;
    }

    public function deleteRecord()
    {
        if(!$this->is_in_group){
            $this->is_delete = true;
            unset($this->status);
            $this->save();
        }
    }

    public static function checkExistingTable(array $data = [])
    {
        if (isset($data['items_is_remove'])) {
            foreach ($data['items_is_remove'] as $key => $value) {
                unset($data['items_name'][$key]);
                unset($data['items_number'][$key]);
            }
        }
        return $data;
    }

    public static function modifyData(array $data = [])
    {
        if (isset($data)) {
            if (method_exists(static::MODEL_NAMESPACE . 'Order', 'checkExistingTable')) {
                $data = static::checkExistingTable($data);
            }
        }
        return $data;
    }

    public static function getUngroupOrder(int $company_id = -1)
    {
        return static::where([['is_in_group', '=', 0], ['is_delete', '=', 0], ['company_id', '=', $company_id]])->whereNull('delivery_date')->get();
    }

    public static function findRecord(int $id = -1)
    {
        if ($orderStatusModel = Model::checkModel('OrderStatus')) {
            $data = static::where('id', $id)->first();
            $orderStatus = $orderStatusModel::getLastestStatusByOrderUuid($data->uuid);
            $data['status'] = (isset($orderStatus)) ? $orderStatus->status : 'Order Created';
            return $data;
        }
        return null;
    }

    public static function findEditableRecord(int $id = -1)
    {
        $record = static::where('id', $id)->first();
        return ($record->is_in_group > 0) ? false : $record;
    }

    public static function findRecordByUuid(String $uuid = '')
    {
        return static::where('uuid', $uuid)->first();
    }

    public static function batchUpdate(array $uuid_list = [])
    {
        return static::whereIn('uuid', $uuid_list)->update(['is_in_group' => true]);
    }
}
