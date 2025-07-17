<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
        use HasFactory;

protected $fillable = [
    'table_number',
    'seats',
    'capacity',
    'location',
    'status',   
    'chair_type',
    'status',
    'min_guests',
    'max_guests',
    'is_smoking_allowed',
    'price_per_hour',
    'description',
];
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
