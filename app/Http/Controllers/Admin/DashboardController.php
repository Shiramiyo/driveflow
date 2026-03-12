<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'cars' => Car::where('is_active', true)->count(),
                'bookings' => Booking::count(),
                'users' => User::count(),
                'revenue' => Booking::sum('total_amount'),
            ],
            'recentBookings' => Booking::with(['car.city', 'user'])
                ->latest()
                ->take(6)
                ->get(),
            'recentCars' => Car::with(['city', 'host'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
