<?php

use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\CarManagementController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('trips.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cars/{car}/checkout', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/cars/{car}/checkout', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/{booking}', [TripController::class, 'show'])->name('trips.show');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/cars', [CarManagementController::class, 'index'])->name('cars.index');
        Route::get('/cars/create', [CarManagementController::class, 'create'])->name('cars.create');
        Route::post('/cars', [CarManagementController::class, 'store'])->name('cars.store');
        Route::get('/cars/{car}/edit', [CarManagementController::class, 'edit'])->name('cars.edit');
        Route::put('/cars/{car}', [CarManagementController::class, 'update'])->name('cars.update');

        Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
        Route::patch('/bookings/{booking}', [BookingManagementController::class, 'update'])->name('bookings.update');

        Route::get('/customers', [CustomerManagementController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}/edit', [CustomerManagementController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerManagementController::class, 'update'])->name('customers.update');
    });

require __DIR__.'/auth.php';
