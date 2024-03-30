<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_payment';
    public $timestamps = true;

    protected $fillable = [
        'mode',
        'amount',
        'id_buyer',
        'id_order',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'id_buyer');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
