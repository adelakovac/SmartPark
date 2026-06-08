<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ParkingLocation extends Model {
    protected $fillable = ['name','address','city','description','total_spots','latitude','longitude','hourly_rate','opening_hours'];
    public function spots() { return $this->hasMany(ParkingSpot::class); }
    public function availableSpots() { return $this->spots()->where('status','available'); }
    public function favorites() { return $this->hasMany(Favorite::class); }
}