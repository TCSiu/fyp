<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class BaseImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    public const MODEL_NAMESPACE 	= '\\App\\Imports\\';

    private ?User $user = null;

    public function __construct($user)
    {
        $this->user = $user;
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
        return trim(static::MODEL_NAMESPACE).trim(str_replace(' ', '', static::getModelName($model))).'Import';
    }

    public static function getModelName(string $model = '')
    {
        return ucwords(trim($model));
    }

    public function processData(array $data = [])
    {
        return $data;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }
}
