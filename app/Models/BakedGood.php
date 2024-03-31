<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakedGood extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'is_available',
        'thumbnail_image_id',
        'description',
        'weight_gram',
    ];

    /**
     * Get the images for the baked good.
     */
    public function images()
    {
        return $this->hasMany(BakedGoodImage::class, 'id_baked_goods');
    }
    public function thumbnailImage()
    {
        return $this->belongsTo(BakedGoodImage::class, 'thumbnail_image_id');
    }
   
    /**
     * Get the ingredients for the baked good.
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'id_baked_goods');
    }
}
