<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends EloquentModel
{
    use HasFactory;

    public const MODEL_NAMESPACE 	= '\\App\\Models\\';
    public const PAGE_TITLE 		= '';
    public const OPERATION	 		= [];
    public const TABLE_FIELDS		= ['id' => 'id'];
    public const ALLOW_ACTIONS 		= ['view'];
    public const VALIDATE_RULES 	= [];
    public const VALIDATE_MESSAGE 	= [];
    public const VIWES_FIELDS 		= ['id' => 'normal'];

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
        return trim(static::MODEL_NAMESPACE).trim(str_replace(' ', '', static::getModelName($model)));
    }

    public static function getModelName(string $model = '')
    {
        return ucwords(trim($model));
    }

    public static function getInpageTitle(int $id = -1)
    {
        return static::PAGE_TITLE . ' ' . $id;
    }

    public static function getCount(int $company_id = -1)
    {
        return static::where('company_id', $company_id)->count();
    }

    public static function getValidateRules(int $id = -1)
    {
        return static::VALIDATE_RULES;
    }

    public static function getData(int $paginate_size = -1, int $company_id = 0)
    {
        if ($paginate_size > 0) {
            return static::where('company_id', $company_id)->paginate($paginate_size);
        }
        return static::all();
    }

    public static function matchField($user = null, array $data = [])
    {
        return $data;
    }

    public static function findRecord(int $id = -1)
    {
        return static::where('id', $id)->first();
    }

    public static function findEditableRecord(int $id = -1)
    {
        return static::findRecord($id);
    }

    public static function modifyData(array $data = [])
    {
        return $data;
    }
}
