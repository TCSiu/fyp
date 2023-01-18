<?php

namespace App\Exports;

use App\Models\Model;
use Maatwebsite\Excel\Concerns\FromCollection;

class BaseImport implements FromCollection
{
    public const MODEL_NAMESPACE 	= '\\App\\Exports\\';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

	public function getCsvSettings(): array{
		return [
			'delimiter' =>  ','
		];
	}

	public function headings(): array{
		return ['#'];
	}

    public function model(array $row){
        return $row;
    }

    public static function checkModel(string $model = ''){
		if(isset($model) && is_string($model)){
			$model = trim($model);
			if(strlen($model) > 0){
				$className = static::getModelClassName($model);
				if(class_exists($className)){
					return $className;
				}
			}
		}
		return false;
	}

	public static function getModelClassName(string $model = ''){
		return trim(static::MODEL_NAMESPACE).trim(str_replace(' ', '', static::getModelName($model))).'Import';
	}

	public static function getModelName(string $model = ''){
		return ucwords(trim($model));
	}

    public function processData(array $data = []){
        return $data;
    }
}
