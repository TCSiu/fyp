<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
	public function headings(): array{
		return ['#', 'id', 'sex', 'first_name', 'last_name', 'phone_number', 'delivery1', 'delivery2', 'lat', 'lng', 'product_name_and_number'];
	}

	public function collection(int $company_id = -1){
		$records = Order::getUngroupOrder($company_id);
        $result = [];
        $count = 0;
        foreach ($records as $record) {
            $result[] = [
                '#'                         =>  ++$count,
                'id'                        =>  $record->id,
                'sex'                       =>  $record->sex,
                'first_name'                =>  $record->first_name,
                'last_name'                 =>  $record->last_name,
                'phone_number'              =>  $reocrd->phone_number,
                'delivery1'                 =>  $reocrd->deliver1,
                'delivery2'                 =>  $record->delivery2
                'lat'                       =>  $record->lat,
                'lng'                       =>  $reocrd->lng,
                'product_name_and_number'   =>  json_encode($reocrd->product_name_and_number),
            ];
        }
        return dd($result);
        return collect($result);
	}
}
