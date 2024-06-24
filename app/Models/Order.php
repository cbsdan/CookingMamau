<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'order_status',
        'buyer_name',
        'buyer_note',
        'delivery_address',
        'email_address',
        'shipping_cost',
        'discount_code',
        'id_schedule',
        'id_buyer',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'id_buyer');
    }

    public function schedule()
    {
        return $this->belongsTo(AvailableSchedule::class, 'id_schedule');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_code', 'discount_code');
    }

    public function orderedGoods()
    {
        return $this->belongsToMany(BakedGood::class, 'ordered_goods', 'id_order', 'id_baked_good')
                    ->withPivot('price_per_good', 'qty')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_order');
    }
}
