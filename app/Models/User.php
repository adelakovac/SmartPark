<?php
namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['name','email','password','role'];
    protected $hidden   = ['password','remember_token'];
    protected function casts(): array {
        return ['email_verified_at'=>'datetime','password'=>'hashed'];
    }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function reservations() { return $this->hasMany(Reservation::class); }
    public function favorites() { return $this->hasMany(Favorite::class); }
    public function reports() { return $this->hasMany(Report::class); }
    public function hasFavorited($locationId): bool {
        return $this->favorites()->where('parking_location_id', $locationId)->exists();
    }
}