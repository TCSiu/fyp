<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class image_usage extends Model
{
    use HasFactory;

    protected $table = 'image_upload';

    protected $fillable = [
        'image_id',
        'usage',
        'usage_id',
    ];
}
