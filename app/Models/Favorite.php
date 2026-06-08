<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {
    protected $fillable = ['user_id', 'parking_location_id'];
    public function user() { return $this->belongsTo(User::class); }
    public function location() { return $this->belongsTo(ParkingLocation::class, 'parking_location_id'); }
}