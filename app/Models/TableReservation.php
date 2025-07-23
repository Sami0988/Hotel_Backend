<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'table_id', 'reservation_time',
        'guests_count', 'status', 'special_requests'
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}

