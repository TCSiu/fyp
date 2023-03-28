<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BaseExport implements FromCollection, WithHeadings
{
    public const MODEL_NAMESPACE 	= '\\App\\Exports\\';

    private int $company_id = -1;

    public function __construct(int $company_id = -1)
    {
        $this->company_id = $company_id;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' =>  ','
        ];
    }

    public function headings(): array
    {
        return ['#'];
    }

    public function model(array $row)
    {
        return $row;
    }

    public static function checkModel(string $model = '')
    {
        if (isset($model) && is_string($model)) {
            $model = trim($model);
            if (strlen($model) > 0) {
                $className = static::getModelClassName($model);
                if (class_exists($className)) {
                    return $className;
                }
            }
        }
        return false;
    }

    public static function getModelClassName(string $model = '')
    {
        return trim(static::MODEL_NAMESPACE).trim(str_replace(' ', '', static::getModelName($model))).'Export';
    }

    public static function getModelName(string $model = '')
    {
        return ucwords(trim($model));
    }

    public function processData(array $data = [])
    {
        return $data;
    }

    public function collection()
    {
        return collect();
    }
}
