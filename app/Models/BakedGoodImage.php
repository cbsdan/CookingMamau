<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakedGoodImage extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'image_path',
        'id_baked_goods',
        'is_thumbnail'
    ];

    /**
     * Get the baked good that owns the image.
     */
    public function bakedGood()
    {
        return $this->belongsTo(BakedGood::class, 'id_baked_goods');
    }
}
