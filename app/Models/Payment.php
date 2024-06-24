<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'mode',
        'amount',
        'id_order',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
