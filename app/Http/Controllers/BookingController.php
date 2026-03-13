<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Support\TripPriceCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Request $request, Car $car, TripPriceCalculator $calculator): View
    {
        $payload = [
            'start_at' => $request->input('start_at', now()->addDay()->setTime(10, 0)->format('Y-m-d\TH:i')),
            'end_at' => $request->input('end_at', now()->addDays(2)->setTime(10, 0)->format('Y-m-d\TH:i')),
            'pickup_option' => $request->input('pickup_option', $car->pickup_options[0] ?? 'self_pickup'),
        ];

        $quote = $calculator->quote(
            $car,
            $payload['start_at'],
            $payload['end_at'],
            $payload['pickup_option'],
        );

        return view('bookings.create', [
            'car' => $car->load(['city', 'host']),
            'quote' => $quote,
            'pickupOptionLabels' => $this->pickupOptionLabels(),
            'paymentMethods' => $this->paymentMethods(),
            'formDefaults' => [
                ...$payload,
                'driver_phone' => $request->user()->customerProfile?->phone ?: $request->user()->phone,
                'driver_license_number' => $request->user()->customerProfile?->driver_license_number ?: $request->user()->driver_license_number,
                'payment_method' => $request->input('payment_method', 'card'),
            ],
        ]);
    }

    public function store(Request $request, Car $car, TripPriceCalculator $calculator): RedirectResponse
    {
        $validated = $request->validate([
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'pickup_option' => ['required', Rule::in($car->pickup_options)],
            'driver_phone' => ['required', 'string', 'max:30'],
            'driver_license_number' => ['required', 'string', 'max:60'],
            'payment_method' => ['required', Rule::in(array_keys($this->paymentMethods()))],
            'card_number' => ['nullable', 'string', 'max:30', 'required_if:payment_method,card'],
            'terms_accepted' => ['accepted'],
        ]);

        $quote = $calculator->quote(
            $car,
            $validated['start_at'],
            $validated['end_at'],
            $validated['pickup_option'],
        );

        $hasConflict = Booking::query()
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_at', '<', $quote['end_at'])
            ->where('end_at', '>', $quote['start_at'])
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors(['start_at' => 'Those dates are no longer available for this car.']);
        }

        $user = $request->user();
        $user->forceFill([
            'phone' => $validated['driver_phone'],
            'driver_license_number' => $validated['driver_license_number'],
        ])->save();

        $customer = Customer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'city_id' => $user->customerProfile?->city_id ?: $car->city_id,
                'name' => $user->name,
                'phone' => $validated['driver_phone'],
                'email' => $user->email,
                'driver_license_number' => $validated['driver_license_number'],
                'address' => $user->customerProfile?->address,
            ],
        );

        $booking = Booking::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'car_id' => $car->id,
            'city_id' => $car->city_id,
            'reference' => $this->generateReference(),
            'status' => 'confirmed',
            'booking_rate' => 'non_refundable',
            'payment_method' => $validated['payment_method'],
            'card_last_four' => $validated['payment_method'] === 'card'
                ? substr((string) $validated['card_number'], -4)
                : null,
            'payment_country' => 'Cambodia',
            'pickup_option' => $validated['pickup_option'],
            'pickup_location' => $car->location_name,
            'dropoff_location' => $car->location_name,
            'start_at' => $quote['start_at'],
            'end_at' => $quote['end_at'],
            'trip_days' => $quote['trip_days'],
            'price_per_day' => $quote['price_per_day'],
            'trip_subtotal' => $quote['trip_subtotal'],
            'protection_fee' => $quote['protection_fee'],
            'delivery_fee' => $quote['delivery_fee'],
            'total_amount' => $quote['total_amount'],
            'driver_phone' => $validated['driver_phone'],
            'driver_license_number' => $validated['driver_license_number'],
            'notes' => null,
            'wants_marketing' => false,
            'terms_accepted' => true,
        ]);

        $car->increment('trips_count');

        return redirect()
            ->route('trips.show', $booking)
            ->with('status', 'Trip booked successfully.');
    }

    private function paymentMethods(): array
    {
        return [
            'card' => 'Card',
            'aba' => 'ABA transfer',
        ];
    }

    private function pickupOptionLabels(): array
    {
        return [
            'self_pickup' => 'Self pickup',
            'airport_meetup' => 'Airport meetup',
            'doorstep_delivery' => 'Doorstep delivery',
        ];
    }

    private function generateReference(): string
    {
        do {
            $reference = 'TRIP-'.strtoupper(Str::random(6));
        } while (Booking::where('reference', $reference)->exists());

        return $reference;
    }
}
