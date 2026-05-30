<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingLocation extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city',
        'description',
        'total_spots',
    ];

    public function spots()
{
    return $this->hasMany(ParkingSpot::class);
}
}
