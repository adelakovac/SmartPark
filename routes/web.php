<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParkingLocationController;
use App\Http\Controllers\ParkingSpotController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/locations');
});

Route::middleware('auth')->group(function () {
    Route::get('/locations', [ParkingLocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [ParkingLocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [ParkingLocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{id}', [ParkingLocationController::class, 'show'])->name('locations.show');

    Route::get('/locations/{id}/spots/create', [ParkingSpotController::class, 'create'])->name('spots.create');
    Route::post('/locations/{id}/spots', [ParkingSpotController::class, 'store'])->name('spots.store');

    Route::post('/spots/{id}/toggle', [ParkingSpotController::class, 'toggle'])->name('spots.toggle');
    Route::get('/spots/{id}/edit', [ParkingSpotController::class, 'edit'])->name('spots.edit');
    Route::post('/spots/{id}/update', [ParkingSpotController::class, 'update'])->name('spots.update');
    Route::post('/spots/{id}/delete', [ParkingSpotController::class, 'destroy'])->name('spots.delete');

    Route::post('/spots/{spotId}/reserve', [ReservationController::class, 'store'])->name('spots.reserve');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    Route::get('/dashboard', [ParkingLocationController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/locations/{id}/generate-spots', [ParkingSpotController::class, 'generate'])->name('spots.generate');
});

require __DIR__.'/auth.php';
