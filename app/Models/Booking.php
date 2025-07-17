<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'bookable_id', 'bookable_type', 'user_id', 'start_time', 'end_time', 'status',
    ];

    // Polymorphic relation to the bookable entity (Room or Table)
    public function bookable()
    {
        return $this->morphTo();
    }

    // Booking belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
