<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\City;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $cities = City::withCount(['cars' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        $featuredCars = Car::with(['city', 'host'])
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', [
            'cities' => $cities,
            'featuredCars' => $featuredCars,
            'defaultStartAt' => now()->addDay()->setTime(10, 0)->format('Y-m-d\TH:i'),
            'defaultEndAt' => now()->addDays(3)->setTime(10, 0)->format('Y-m-d\TH:i'),
        ]);
    }
}
