<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qty',
        'unit',
        'image_path',
        'id_baked_goods',
    ];

    /**
     * Get the baked good that owns the ingredient.
     */
    public function bakedGood()
    {
        return $this->belongsTo(BakedGood::class, 'id_baked_goods');
    }
}
