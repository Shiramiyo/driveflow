<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.cars.index', [
            'cars' => Car::with(['city', 'host'])->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.cars.form', [
            'car' => new Car,
            'cities' => City::orderBy('name')->get(),
            'hosts' => User::where('role', 'host')->orderBy('name')->get(),
            'pickupOptionChoices' => $this->pickupOptionChoices(),
            'formAction' => route('admin.cars.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Car::create($this->validatedPayload($request));

        return redirect()
            ->route('admin.cars.index')
            ->with('status', 'Car added successfully.');
    }

    public function edit(Car $car): View
    {
        return view('admin.cars.form', [
            'car' => $car,
            'cities' => City::orderBy('name')->get(),
            'hosts' => User::where('role', 'host')->orderBy('name')->get(),
            'pickupOptionChoices' => $this->pickupOptionChoices(),
            'formAction' => route('admin.cars.update', $car),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        $car->update($this->validatedPayload($request, $car));

        return redirect()
            ->route('admin.cars.index')
            ->with('status', 'Car details updated.');
    }

    private function validatedPayload(Request $request, ?Car $car = null): array
    {
        $validated = $request->validate([
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'host_id' => ['required', Rule::exists('users', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'between:2018,2026'],
            'car_type' => ['required', 'string', 'max:255'],
            'transmission' => ['required', 'string', 'max:50'],
            'seats' => ['required', 'integer', 'between:2,9'],
            'fuel_type' => ['required', 'string', 'max:100'],
            'location_name' => ['required', 'string', 'max:255'],
            'price_per_day' => ['required', 'numeric', 'min:20'],
            'rating' => ['required', 'numeric', 'between:1,5'],
            'trips_count' => ['required', 'integer', 'min:0'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_url' => ['required', 'url'],
            'gallery_input' => ['nullable', 'string'],
            'features_input' => ['nullable', 'string'],
            'pickup_options' => ['required', 'array', 'min:1'],
            'pickup_options.*' => ['string', Rule::in(array_keys($this->pickupOptionChoices()))],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'instant_book' => ['nullable', 'boolean'],
        ]);

        $slugBase = Str::slug($validated['name'].' '.$validated['year']);
        $slug = $slugBase;

        if (! $car || $car->slug !== $slugBase) {
            $suffix = 2;

            while (Car::query()
                ->when($car, fn ($query) => $query->whereKeyNot($car->id))
                ->where('slug', $slug)
                ->exists()) {
                $slug = $slugBase.'-'.$suffix;
                $suffix++;
            }
        } elseif ($car) {
            $slug = $car->slug;
        }

        return [
            'city_id' => $validated['city_id'],
            'host_id' => $validated['host_id'],
            'slug' => $slug,
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'car_type' => $validated['car_type'],
            'transmission' => $validated['transmission'],
            'seats' => $validated['seats'],
            'fuel_type' => $validated['fuel_type'],
            'location_name' => $validated['location_name'],
            'price_per_day' => $validated['price_per_day'],
            'rating' => $validated['rating'],
            'trips_count' => $validated['trips_count'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'],
            'gallery' => $this->linesToArray($validated['gallery_input'] ?: $validated['image_url']),
            'features' => $this->linesToArray($validated['features_input'] ?: ''),
            'pickup_options' => $validated['pickup_options'],
            'delivery_fee' => $validated['delivery_fee'],
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'instant_book' => (bool) ($validated['instant_book'] ?? false),
        ];
    }

    private function linesToArray(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function pickupOptionChoices(): array
    {
        return [
            'self_pickup' => 'Self pickup',
            'airport_meetup' => 'Airport meetup',
            'doorstep_delivery' => 'Doorstep delivery',
        ];
    }
}
