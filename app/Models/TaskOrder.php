<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause as JoinClause;

class TaskOrder extends Model
{
    use HasFactory;

    protected $table = 'task_order';

    protected $fillable = [
		'task_uuid',
        'order_uuid',
	];

    public static function batchCreate(String $task_uuid, array $order_uuid = []){
        $temp = [];
        foreach($order_uuid as $key => $value){
            $temp[$key]['task_uuid']        = $task_uuid;
            $temp[$key]['order_uuid']      = $value;
            static::create($temp[$key]);
        }
    }

    public static function getOrdersByTaskUuid(String $task_uuid){
        return static::select(['order_uuid'])->where('task_uuid', $task_uuid)->get();
    }

    public static function getTaskByOrderUuid(String $order_uuid){
        return static::select(['task_uuid'])->where('order_uuid', $order_uuid)->first();
    }
}
