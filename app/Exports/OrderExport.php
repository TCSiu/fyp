<?php

namespace App\Exports;

use App\Exports\BaseExport;
use App\Models\Order;
use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport extends BaseExport
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(int $company_id = -1){
        $this->company_id = $company_id;
    }

	public function headings(): array{
		return ['#', 'id', 'sex', 'first_name', 'last_name', 'phone_number', 'delivery1', 'delivery2', 'lat', 'lng', 'product_name_and_number', 'demand'];
	}

	public function collection(){
        $result = [];
        $warehouse = Company::findRecord($this->company_id);
        $records = Order::getUngroupOrder($this->company_id);
        $count = 0;
        $result[] = [
            '#'                         =>  $count++,
            'id'                        =>  '',
            'sex'                       =>  '',
            'first_name'                =>  '',
            'last_name'                 =>  '',
            'phone_number'              =>  '',
            'delivery1'                 =>  $warehouse->deliver1,
            'delivery2'                 =>  '',
            'lat'                       =>  $warehouse->lat,
            'lng'                       =>  $warehouse->lng,
            'product_name_and_number'   =>  '',
            'demand'                    =>  0,
        ];
        foreach ($records as $record) {
            $result[] = [
                '#'                         =>  $count++,
                'id'                        =>  $record->id,
                'sex'                       =>  $record->sex,
                'first_name'                =>  $record->first_name,
                'last_name'                 =>  $record->last_name,
                'phone_number'              =>  $record->phone_number,
                'delivery1'                 =>  $record->deliver1,
                'delivery2'                 =>  $record->delivery2,
                'lat'                       =>  $record->lat,
                'lng'                       =>  $record->lng,
                'product_name_and_number'   =>  json_encode($record->product_name_and_number),
                'demand'                    =>  static::countDemand($record->product_name_and_number),
            ];
        }
        return collect($result);
	}

    public static function countDemand(string $product_name_and_number = ''){
        $data = json_decode($product_name_and_number, true);
        $demand = 0;
        foreach($data as $key => $value){
            $demand += intval($value['product_number']);
        }
        return $demand;
    }
}
