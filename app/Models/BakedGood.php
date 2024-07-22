<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BakedGood extends Model
{
    use HasFactory, Searchable;

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

    public function toSearchableArray() {
        $array = $this->toArray();
        $array['name'] =  $this->name;
        $array['price'] = (float) $this->price;
        $array['description'] = $this->description;

        return $array;
    }
}
