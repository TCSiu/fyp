<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class ImageUpload extends Model
{
    use HasFactory;

    protected $table = 'image_upload';

    protected $fillable = [
        'image',
        'path',
        'company_id',
    ];

    public static function getValidateRules(int $id = -1)
    {
        return [
            'file' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ];
    }

    public static function getData(int $paginate_size = -1, int $company_id = 0)
    {
        if ($paginate_size > 0) {
            return static::where(['company_id' => $company_id])->paginate($paginate_size);
        }
        return static::where(['company_id' => $company_id])->get();
    }
}
