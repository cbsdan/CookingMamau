<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $primaryKey = 'discount_code';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'discount_code',
        'percent',
        'max_number_buyer',
        'min_order_price',
        'is_one_time_use',
        'discount_start',
        'discount_end',
        'image_path',
        'max_discount_amount',
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'discount_code', 'discount_code');
    }
}
