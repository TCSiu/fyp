<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'order_group';

    public const PAGE_TITLE = 'Order Group';
    public const INPAGE_TITLE = 'View Order Group';

    // protected $fillable = [
    //     'product_name',
    //     'no_of_items',
    //     'address',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'address',
    // ];

    public static function getTitle(){
        return static::PAGE_TITLE;
    }

    public static function getData(){
        $data = static::all();
    }
}
