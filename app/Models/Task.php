<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Support\Str;
use Validator;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    public const PAGE_TITLE 		= 'Task';
    public const OPERATION			= ['route_planning'];
    public const ALLOW_ACTIONS 		= ['view', 'assign'];
    public const TABLE_FIELDS 		= ['uuid' => 'uuid', 'status' => 'status'];

    protected $fillable = [
        'company_id',
        'route_order',
        'relative_staff',
        'status',
    ];

    public const VIWES_FIELDS = [
        'status'				=> 'normal',
        'relative_staff'		=> 'table_json.first_name/last_name/email/phone',
        'route_order'			=> 'table.first_name/last_name/phone_number/delivery1/delivery2/status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!(isset($model->uuid) && is_string($model->uuid) && strlen($model->uuid) > 0)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public static function getTitle()
    {
        return static::PAGE_TITLE;
    }

    public static function findRecord(int $id = -1)
    {
        $data = static::where('id', $id)->first();
        $taskOrder = TaskOrder::getOrdersByTaskUuid($data->uuid)->toArray();
        $orderStatus = OrderStatus::getBatchStatusByUuid($taskOrder);
        $route_order = json_decode($data->route_order, true);
        foreach ($route_order as $key => $value) {
            $target_uuid = $value['uuid'];
            $value['status'] = $orderStatus[$target_uuid]['status'];
            $route_order[$key] = $value;
        }
        $staff_info = [];
        if (isset($data->relative_staff)) {
            $relative_staff = Account::findRecord($data->relative_staff);
            if ($relative_staff instanceof Account) {
                $staff_info = [
                    'first_name' => $relative_staff->first_name,
                    'last_name' => $relative_staff->last_name,
                    'email' => $relative_staff->email,
                    'phone' => $relative_staff->phone,
                ];
            }
        }
        $data['route_order'] = $route_order;
        $data['relative_staff'] = $staff_info;
        return $data;
    }

    public static function findRecordByStaffId(int $staff_id = -1)
    {
        return static::select(['id', 'uuid', 'status', 'updated_at'])
            ->where('relative_staff', $staff_id)
            ->where('status', '<>', 'finished')
            ->get();
    }

    public static function findRecordByUuid(string $uuid = '')
    {
        return static::where('uuid', $uuid)->first();
    }

    public static function getRouteUuid(string $uuid = '')
    {
        $task = static::where('uuid', $uuid)->first();
        if (!($task instanceof Task)) {
            return false;
        }
        $routes = json_decode($task->route_order, true);
        return array_column($routes, 'uuid');
    }

    public static function initOrder(array $input = [])
    {
        $request = [];
        $uuid_list = [];
        $rules = [
            'uuid'		=> 'required|array',
            'uuid.*'	=> 'exists:order,uuid',
        ];
        foreach ($input as $row) {
            array_push($uuid_list, $row['uuid']);
        }
        $request['uuid'] = $uuid_list;
        $validator = Validator::make($request, $rules);
        if ($validator->fails()) {
            return false;
        }
        return json_encode($input);
    }

    public static function getOrderUuid(array $input = [])
    {
        return array_column($input, 'uuid');
    }

    public static function updateStatus(string $uuid = '')
    {
        $routes = TaskOrder::getOrdersByTaskUuid($uuid)->toArray();
        $status_count = OrderStatus::countStatus($routes);
        if (sizeof($status_count) === 1) {
            $target_status = array_keys($status_count)[0];
            return static::where('uuid', $uuid)->update(['status' => $target_status]);
        }
        return false;
    }

    public static function assignTask(int $order_id = -1, int $staff_id = -1)
    {
        if (Order::where('id', $order_id)->count() === 0) {
            throw new \Exception(sprintf('Order %d not found when assigning task.', $order_id));
        }
        if (Account::where('id', $staff_id)->count() === 0) { // Amble says User no use, use Account instead!
            throw new \Exception(sprintf('Staff %d not found when assigning task.', $staff_id));
        }
        return static::where('id', $order_id)->update(['relative_staff' => $staff_id]);
    }
}
