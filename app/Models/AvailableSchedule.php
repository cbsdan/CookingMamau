<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvailableSchedule extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'schedule',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_schedule');
    }
}
