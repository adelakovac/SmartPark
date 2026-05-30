<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    protected $fillable = [
        'parking_location_id',
        'spot_number',
        'status',
        'type',
    ];

    public function location()
    {
        return $this->belongsTo(ParkingLocation::class, 'parking_location_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}