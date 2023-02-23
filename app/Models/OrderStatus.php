<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;


class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status';

    static function batchCreate(array $uuid = []){
        
    }
}
