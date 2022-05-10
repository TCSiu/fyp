<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Model extends EloquentModel
{
    use HasFactory;

    public const MODEL_NAMESPACE = '\\App\\Models\\';
    public const PAGE_TITLE = 'View Page';
    public const INPAGE_TITLE = 'Default View Page';
    public const TARGET_FIELD = ['id'];

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
    }

    public static function getModelClassName(string $model = ''){
        return trim(static::MODEL_NAMESPACE).trim(str_replace(' ', '', static::getModelName($model)));
    }

    public static function getModelName(string $model = ''){
        return ucwords(trim($model));
    }

    public static function getPageTitle(){
        return static::PAGE_TITLE;
    }

    public static function getInpageTitle(){
        return static::INPAGE_TITLE;
    }

    public static function getTargetField(){
        return static::TARGET_FIELD;
    }
}