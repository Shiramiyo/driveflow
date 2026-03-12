<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoUser = User::where('email', 'demo@driveflow.test')->first();
        $traveler = User::where('email', 'traveler@driveflow.test')->first();

        if (! $demoUser || ! $traveler) {
            return;
        }

        foreach ([
            [
                'user' => $demoUser,
                'car_slug' => 'bmw-330e-riverline',
                'start' => Carbon::now()->addDays(7)->setTime(9, 0),
                'end' => Carbon::now()->addDays(10)->setTime(9, 0),
                'pickup_option' => 'doorstep_delivery',
                'booking_rate' => 'refundable',
                'payment_method' => 'card',
                'card_last_four' => '4242',
                'payment_country' => 'Cambodia',
                'driver_phone' => $demoUser->phone,
                'driver_license_number' => $demoUser->driver_license_number,
                'notes' => 'Need child seat for airport arrival.',
                'wants_marketing' => true,
            ],
            [
                'user' => $traveler,
                'car_slug' => 'toyota-fortuner-altitude',
                'start' => Carbon::now()->addDays(12)->setTime(10, 0),
                'end' => Carbon::now()->addDays(14)->setTime(10, 0),
                'pickup_option' => 'airport_meetup',
                'booking_rate' => 'non_refundable',
                'payment_method' => 'aba',
                'card_last_four' => null,
                'payment_country' => 'Cambodia',
                'driver_phone' => $traveler->phone,
                'driver_license_number' => $traveler->driver_license_number,
                'notes' => 'Arriving with two large suitcases.',
                'wants_marketing' => false,
            ],
        ] as $seedBooking) {
            $car = Car::where('slug', $seedBooking['car_slug'])->first();

            if (! $car) {
                continue;
            }

            $customer = Customer::updateOrCreate(
                ['user_id' => $seedBooking['user']->id],
                [
                    'city_id' => $car->city_id,
                    'name' => $seedBooking['user']->name,
                    'phone' => $seedBooking['driver_phone'],
                    'email' => $seedBooking['user']->email,
                    'driver_license_number' => $seedBooking['driver_license_number'],
                ],
            );

            $days = max(1, (int) ceil($seedBooking['start']->diffInMinutes($seedBooking['end']) / 1440));
            $subtotal = $days * (float) $car->price_per_day;
            $protectionFee = $seedBooking['booking_rate'] === 'refundable' ? round($subtotal * 0.12, 2) : 0;
            $deliveryFee = match ($seedBooking['pickup_option']) {
                'airport_meetup' => (float) $car->delivery_fee,
                'doorstep_delivery' => (float) $car->delivery_fee + 18,
                default => 0,
            };

            Booking::updateOrCreate(
                ['user_id' => $seedBooking['user']->id, 'car_id' => $car->id, 'start_at' => $seedBooking['start']],
                [
                    'customer_id' => $customer->id,
                    'city_id' => $car->city_id,
                    'reference' => 'TRIP-'.strtoupper(Str::random(6)),
                    'status' => 'confirmed',
                    'booking_rate' => $seedBooking['booking_rate'],
                    'payment_method' => $seedBooking['payment_method'],
                    'card_last_four' => $seedBooking['card_last_four'],
                    'payment_country' => $seedBooking['payment_country'],
                    'pickup_option' => $seedBooking['pickup_option'],
                    'pickup_location' => $car->location_name,
                    'dropoff_location' => $car->location_name,
                    'end_at' => $seedBooking['end'],
                    'trip_days' => $days,
                    'price_per_day' => $car->price_per_day,
                    'trip_subtotal' => $subtotal,
                    'protection_fee' => $protectionFee,
                    'delivery_fee' => $deliveryFee,
                    'total_amount' => $subtotal + $protectionFee + $deliveryFee,
                    'driver_phone' => $seedBooking['driver_phone'],
                    'driver_license_number' => $seedBooking['driver_license_number'],
                    'notes' => $seedBooking['notes'],
                    'wants_marketing' => $seedBooking['wants_marketing'],
                    'terms_accepted' => true,
                ],
            );
        }
    }
}
