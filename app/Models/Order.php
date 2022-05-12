<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    public const PAGE_TITLE = 'Order';
    public const INPAGE_TITLE = 'View Order';
    public const TARGET_FIELD = ['id', 'delivery_date', 'address', 'is_in_group', 'is_complete'];
    public const ALLOW_ACTIONS = ['view', 'edit', 'delete'];

    protected $fillable = [
        'sex',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'delivery_date',
        'product_name_and_number',
        'is_in_group',
        'is_complete',
        'is_delete',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'address',
        'phone_number',
    ];

    public static function getData(){
        return static::where('is_delete', 0);
    }
}
