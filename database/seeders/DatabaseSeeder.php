<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Driveflow Admin',
                'email' => 'admin@driveflow.test',
                'phone' => '+66 81 555 1000',
                'driver_license_number' => 'TH-ADMIN-001',
                'role' => 'admin',
            ],
            [
                'name' => 'Narin Host',
                'email' => 'narin.host@driveflow.test',
                'phone' => '+66 81 555 1001',
                'driver_license_number' => 'TH-HOST-101',
                'role' => 'host',
            ],
            [
                'name' => 'Mali Host',
                'email' => 'mali.host@driveflow.test',
                'phone' => '+66 81 555 1002',
                'driver_license_number' => 'TH-HOST-102',
                'role' => 'host',
            ],
            [
                'name' => 'Preecha Host',
                'email' => 'preecha.host@driveflow.test',
                'phone' => '+66 81 555 1003',
                'driver_license_number' => 'TH-HOST-103',
                'role' => 'host',
            ],
            [
                'name' => 'Demo Driver',
                'email' => 'demo@driveflow.test',
                'phone' => '+66 81 555 2001',
                'driver_license_number' => 'TH-CUST-201',
                'role' => 'customer',
            ],
            [
                'name' => 'Traveler One',
                'email' => 'traveler@driveflow.test',
                'phone' => '+66 81 555 2002',
                'driver_license_number' => 'TH-CUST-202',
                'role' => 'customer',
            ],
        ] as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ],
            );
        }

        $this->call([
            CitySeeder::class,
            CarSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
