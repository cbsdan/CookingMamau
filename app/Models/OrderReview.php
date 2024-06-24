<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'rating',
        'comment',
        'id_order',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
    public function orderedGoods()
    {
        return $this->order->orderedGoods();
    }
    public function reviewImages()
    {
        return $this->hasMany(ReviewImage::class, 'id_review');
    }
}
