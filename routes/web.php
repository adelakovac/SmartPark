<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParkingLocationController;
use App\Http\Controllers\ParkingSpotController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/map');
});

Route::middleware('auth')->group(function () {
    Route::get('/map',       [ParkingLocationController::class, 'map'])->name('map');
    Route::get('/dashboard', [ParkingLocationController::class, 'dashboard'])->name('dashboard');

    Route::get('/locations', [ParkingLocationController::class, 'index'])->name('locations.index');

    Route::middleware('admin')->group(function () {
        // !! create and edit MUST be before /{id} !!
        Route::get('/locations/create',        [ParkingLocationController::class, 'create'])->name('locations.create');
        Route::post('/locations',              [ParkingLocationController::class, 'store'])->name('locations.store');
        Route::get('/locations/{id}/edit',     [ParkingLocationController::class, 'edit'])->name('locations.edit');
        Route::post('/locations/{id}/update',  [ParkingLocationController::class, 'update'])->name('locations.update');
        Route::post('/locations/{id}/delete',  [ParkingLocationController::class, 'destroy'])->name('locations.delete');

        Route::get('/locations/{id}/spots/create',    [ParkingSpotController::class, 'create'])->name('spots.create');
        Route::post('/locations/{id}/spots',          [ParkingSpotController::class, 'store'])->name('spots.store');
        Route::post('/locations/{id}/generate-spots', [ParkingSpotController::class, 'generate'])->name('spots.generate');
        Route::post('/spots/{id}/toggle',             [ParkingSpotController::class, 'toggle'])->name('spots.toggle');
        Route::get('/spots/{id}/edit',                [ParkingSpotController::class, 'edit'])->name('spots.edit');
        Route::post('/spots/{id}/update',             [ParkingSpotController::class, 'update'])->name('spots.update');
        Route::post('/spots/{id}/delete',             [ParkingSpotController::class, 'destroy'])->name('spots.delete');

        Route::get('/admin/reservations',              [ReservationController::class, 'adminIndex'])->name('admin.reservations');
        Route::post('/admin/reservations/{id}/cancel', [ReservationController::class, 'adminCancel'])->name('admin.reservations.cancel');

        Route::get('/admin/users',               [UserController::class, 'index'])->name('admin.users');
        Route::post('/admin/users/{id}/promote', [UserController::class, 'promote'])->name('admin.users.promote');
        Route::post('/admin/users/{id}/demote',  [UserController::class, 'demote'])->name('admin.users.demote');
        Route::post('/admin/users/{id}/delete',  [UserController::class, 'destroy'])->name('admin.users.delete');
    });

    // !! /{id} comes AFTER all named static routes !!
    Route::get('/locations/{id}', [ParkingLocationController::class, 'show'])->name('locations.show');

    Route::post('/spots/{spotId}/reserve',   [ReservationController::class, 'store'])->name('spots.reserve');
    Route::get('/reservations',              [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';