<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_baked_good',
        'qty',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function bakedGood()
    {
        return $this->belongsTo(BakedGood::class, 'id_baked_good');
    }
}
