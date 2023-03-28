<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Company;

class OrderExport extends BaseExport
{
    private int $company_id = -1;

    public function __construct(int $company_id = -1)
    {
        $this->company_id = $company_id;
    }

    public function headings(): array
    {
        return ['#', 'uuid', 'sex', 'first_name', 'last_name', 'phone_number', 'delivery1', 'delivery2', 'lat', 'lng', 'demand'];
    }

    public function collection()
    {
        $result = [];
        $warehouse = Company::findRecord($this->company_id);
        $records = Order::getUngroupOrder($this->company_id);
        $count = 0;
        $total_demand = 0;
        $result[] = [
            '#'                     => $count++,
            'uuid'                  => '',
            'sex'                   => '',
            'first_name'            => '',
            'last_name'             => '',
            'phone_number'          => '',
            'delivery1'             => $warehouse->warehouse_address1,
            'delivery2'             => '',
            'lat'                   => $warehouse->lat,
            'lng'                   => $warehouse->lng,
            'demand'                => 0,
        ];
        foreach ($records as $record) {
            $record_demand = static::countDemand($record->product_name_and_number);
            $result[] = [
                '#'                 => $count++,
                'uuid'              => $record->uuid,
                'sex'               => $record->sex,
                'first_name'        => $record->first_name,
                'last_name'         => $record->last_name,
                'phone_number'      => $record->phone_number,
                'delivery1'         => $record->delivery1,
                'delivery2'         => (isset($record->delivery2) && is_null($record->delivery2)) ? $record->delivery2 : '',
                'lat'               => $record->lat,
                'lng'               => $record->lng,
                'demand'            => $record_demand,
            ];
            $total_demand += $record_demand;
        }
        $result[0]['demand'] = $total_demand;
        return collect($result);
    }

    public static function countDemand(string $product_name_and_number = '')
    {
        $data = json_decode($product_name_and_number, true);
        $demand = 0;
        foreach ($data as $key => $value) {
            $demand += intval($value['product_number']);
        }
        return $demand;
    }
}
