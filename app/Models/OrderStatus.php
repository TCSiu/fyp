<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause as JoinClause;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status';

    protected $fillable = [
        'uuid',
        'status',
    ];

    public static function batchCreate(array $uuid = [])
    {
        $temp = [];
        foreach ($uuid as $key => $value) {
            $temp[$key]['uuid'] = $value;
            $temp[$key]['status'] = 'preparing';
            static::create($temp[$key]);
        }
    }

    public static function getBatchStatusByUuid(array $routes_uuid = [])
    {
        $output = [];
        $subquery = self::select(['uuid', DB::raw('MAX(created_at) AS latest')])->whereIn('uuid', $routes_uuid)->groupBy('uuid');
        $status = self::select(['id', 'order_status.uuid', 'status', 'created_at'])->joinSub($subquery, 'b', function (JoinClause $join) {
            $join->on('order_status.uuid', '=', 'b.uuid');
        })->orderBy('id')->get();
        $temp = $status->toArray();
        foreach ($temp as $key => $value) {
            $output[$value['uuid']] = $value;
        }
        return $output;
    }

    public static function updateStatus(String $uuid = '', String $status = '')
    {
        $status_list = ['preparing', 'delivering', 'finished'];
        $isExist = static::where([['uuid', '=', $uuid], ['status', '=', $status]])->exists();
        if (!$isExist) {
            $index = array_search($status, $status_list);
            if ($index > 0) {
                $isCanCreate = static::where([['uuid', '=', $uuid], ['status', '=', $status_list[$index - 1]]])->exists();
                if ($isCanCreate) {
                    return static::create(['uuid' => $uuid, 'status' => $status]);
                }
            }
        }
        return false;
    }

    public static function countStatus(array $routes_uuid = [])
    {
        $output = [];
        $subquery = self::select(['uuid', DB::raw('MAX(created_at) AS latest')])
            ->whereIn('uuid', $routes_uuid)
            ->groupBy('uuid');
        $status_count = self::select(['status', DB::raw('count(status) as count')])
            ->joinSub($subquery, 'b', function (JoinClause $join) {
                $join->on('order_status.uuid', '=', 'b.uuid')
                    ->on('order_status.created_at', '=', 'b.latest');
            })
            ->groupBy('status')
            ->get()
            ->toArray();
        foreach ($status_count as $value) {
            $output[$value['status']] = $value['count'];
        }
        return $output;
    }

    public static function getOrderAllStatus(String $order_uuid = '')
    {
        return static::where('uuid', $order_uuid)->get();
    }

    public static function getLastestStatusByOrderUuid(String $order_uuid = '')
    {
        return static::where('uuid', $order_uuid)->orderBy('created_at', 'DESC')->first();
    }
}
