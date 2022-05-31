<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithCustomCsvSettings, WithHeadings 
{
	/**
	* @return \Illuminate\Support\Collection
	*/

	public function getCsvSettings(): array{
		return [
			'delimiter' =>  ','
		];
	}
	public function headings(): array{
		return ['#', 'lat', 'lng', 'Location'];
	}

	public function collection(){
		return Order::where('id', 'lat', 'lng', 'deliver1')->where('is_delete', 0)->where('is_in_group', 0)->where('is_complete', 0)->get();
	}
}
