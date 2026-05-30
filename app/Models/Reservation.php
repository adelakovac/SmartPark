<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'parking_spot_id',
        'user_id',
        'reserved_at',
    ];

    public function spot()
    {
        return $this->belongsTo(ParkingSpot::class, 'parking_spot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}