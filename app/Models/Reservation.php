<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'parking_spot_id',
        'user_id',
        'user_name',
        'reserved_at',
        'expires_at',
        'duration_hours',
        'total_cost',
        'deposit_amount',
        'deposit_rate',
    ];

    protected $casts = [
        'reserved_at'    => 'datetime',
        'expires_at'     => 'datetime',
        'total_cost'     => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'deposit_rate'   => 'decimal:4',
    ];

    public function spot()
    {
        return $this->belongsTo(ParkingSpot::class, 'parking_spot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}