<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;
use Illuminate\Support\Facades\Storage;

class ImageUsage extends Model
{
    use HasFactory;

    protected $table = 'image_usages';

    protected $fillable = [
        'image_id',
        'usage',
        'usage_id',
    ];

    public static function fileUsageStore(string $model = '', int $id = -1, array $data = []){
        if(array_key_exists('image_selection', $data)){
            $record = static::updateOrCreate(['usage' => $model, 'usage_id' => $id], ['image_id' => $data['image_selection']]);
            return $record;
        }
        return false;
    }

    public static function getImages(string $usage = '', int $usage_id = -1){
        $imageUsage = static::where(['usage' => $usage, 'usage_id' => $usage_id])->get();
        $imageUsage = $imageUsage->toarray();
        $images = [];

        if(isset($imageUsage) && !empty($imageUsage)){
            if(count($imageUsage) > 1){
                forEach($imageUsage as $row){
                    $image = ImageUpload::find($row['image_id']);
                    array_push($images, Storage::disk('upload')->url($image->image));
                }
            }else{
                $image = ImageUpload::find($imageUsage[0]['image_id']);
                return Storage::disk('upload')->url($image->image);
            }
        }
        return $images;
    }
}
