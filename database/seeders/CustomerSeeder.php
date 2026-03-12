<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::whereIn('slug', ['phnom-penh', 'poipet'])->get()->keyBy('slug');
        $customers = [
            'demo@driveflow.test' => [
                'city_slug' => 'phnom-penh',
                'address' => 'Toul Kork, Phnom Penh',
            ],
            'traveler@driveflow.test' => [
                'city_slug' => 'poipet',
                'address' => 'Poipet City, Banteay Meanchey',
            ],
        ];

        foreach ($customers as $email => $profile) {
            $user = User::where('email', $email)->first();
            $city = $cities->get($profile['city_slug']);

            if (! $user) {
                continue;
            }

            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'city_id' => $city?->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'driver_license_number' => $user->driver_license_number,
                    'address' => $profile['address'],
                ],
            );
        }
    }
}
