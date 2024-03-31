<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedGood extends Model
{
    use HasFactory;

    protected $primaryKey = null; // Since it's a composite primary key
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_order',
        'id_baked_goods',
        'price_per_good',
        'qty',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function meal()
    {
        return $this->belongsTo(BakedGood::class, 'id_baked_goods');
    }
}
