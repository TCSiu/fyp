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
    ];

    public static function getValidateRules(int $id = -1){
        return [
            'file' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ];
    }
}
