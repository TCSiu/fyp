<?php

namespace App\Imports;

use App\Models\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseImport implements ToModel, WithHeadingRow
{
    public const MODEL_NAMESPACE 	= '\\App\\Imports\\';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

	public function  __construct($user){
        $this->user = $user;
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
