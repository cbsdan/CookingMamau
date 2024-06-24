<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakedGood extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'price',
        'is_available',
        'description',
        'weight_gram',
    ];

    public function images()
    {
        return $this->hasMany(BakedGoodImage::class, 'id_baked_goods');
    }
    public function orderedGoods()
    {
        return $this->belongsToMany(Order::class, 'ordered_goods', 'id_baked_good', 'id_order')
                    ->withPivot('price_per_good', 'qty')
                    ->withTimestamps();
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'baked_good_ingredients', 'id_baked_goods', 'id_ingredients')
                    ->withPivot('qty')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return OrderReview::whereIn('id_order', $this->orderedGoods()->pluck('orders.id')->toArray())
            ->get();
    }
}
