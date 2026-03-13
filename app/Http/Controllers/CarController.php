<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\City;
use App\Support\TripPriceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(Request $request): View
    {
        $query = Car::query()
            ->with(['city', 'host'])
            ->where('is_active', true);

        if ($request->filled('city')) {
            $city = trim((string) $request->input('city'));
            $query->whereHas('city', function ($cityQuery) use ($city): void {
                $cityQuery
                    ->where('slug', $city)
                    ->orWhere('name', 'like', '%'.$city.'%');
            });
        }

        foreach (['brand', 'car_type', 'transmission', 'fuel_type'] as $filter) {
            $values = collect(Arr::wrap($request->input($filter)))->filter()->values();

            if ($values->isNotEmpty()) {
                $query->whereIn($filter, $values);
            }
        }

        if ($request->filled('seats')) {
            $query->where('seats', '>=', (int) $request->input('seats'));
        }

        if ($request->filled('pickup_option')) {
            $query->whereJsonContains('pickup_options', $request->input('pickup_option'));
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', (float) $request->input('max_price'));
        }

        match ($request->input('sort', 'featured')) {
            'price_low' => $query->orderBy('price_per_day'),
            'price_high' => $query->orderByDesc('price_per_day'),
            'newest' => $query->orderByDesc('year'),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating'),
        };

        return view('cars.index', [
            'cars' => $query->paginate(9)->withQueryString(),
            'filterOptions' => [
                'cities' => City::orderBy('name')->get(['name', 'slug']),
                'brands' => Car::query()->select('brand')->distinct()->orderBy('brand')->pluck('brand'),
                'carTypes' => Car::query()->select('car_type')->distinct()->orderBy('car_type')->pluck('car_type'),
                'fuelTypes' => Car::query()->select('fuel_type')->distinct()->orderBy('fuel_type')->pluck('fuel_type'),
                'transmissions' => Car::query()->select('transmission')->distinct()->orderBy('transmission')->pluck('transmission'),
                'pickupOptions' => $this->pickupOptionLabels(),
            ],
            'defaultStartAt' => $request->input('start_at', now()->addDay()->setTime(10, 0)->format('Y-m-d\TH:i')),
            'defaultEndAt' => $request->input('end_at', now()->addDays(3)->setTime(10, 0)->format('Y-m-d\TH:i')),
        ]);
    }

    public function show(Request $request, Car $car, TripPriceCalculator $calculator): View
    {
        $startAt = $request->input('start_at', now()->addDay()->setTime(10, 0)->format('Y-m-d\TH:i'));
        $endAt = $request->input('end_at', now()->addDays(2)->setTime(10, 0)->format('Y-m-d\TH:i'));
        $pickupOption = $request->input('pickup_option', $car->pickup_options[0] ?? 'self_pickup');

        $quote = $calculator->quote($car, $startAt, $endAt, $pickupOption);

        return view('cars.show', [
            'car' => $car->load(['city', 'host']),
            'pickupOptionLabels' => $this->pickupOptionLabels(),
            'quote' => $quote,
            'selectedStartAt' => $startAt,
            'selectedEndAt' => $endAt,
            'selectedPickupOption' => $pickupOption,
        ]);
    }

    private function pickupOptionLabels(): array
    {
        return [
            'self_pickup' => 'Self pickup',
            'airport_meetup' => 'Airport meetup',
            'doorstep_delivery' => 'Doorstep delivery',
        ];
    }
}
