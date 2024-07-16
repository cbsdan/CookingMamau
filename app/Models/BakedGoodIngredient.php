<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakedGoodIngredient extends Model
{
    use HasFactory;

    protected $fillable = ['id_baked_goods', 'id_ingredients', 'qty'];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
    public function bakedGoods()
    {
        return $this->belongsTo(BakedGood::class);
    }
}
