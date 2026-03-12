<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Phnom Penh',
                'slug' => 'phnom-penh',
                'state' => 'Phnom Penh',
                'country' => 'Cambodia',
                'hero_image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Riverfront drives, business-district pickups, and premium daily commuters.',
            ],
            [
                'name' => 'Poipet',
                'slug' => 'poipet',
                'state' => 'Banteay Meanchey',
                'country' => 'Cambodia',
                'hero_image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Border pickups, casino-district runs, and flexible road-trip access.',
            ],
            [
                'name' => 'Sihanoukville',
                'slug' => 'sihanoukville',
                'state' => 'Preah Sihanouk',
                'country' => 'Cambodia',
                'hero_image' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Beach-ready convertibles, hybrid cruisers, and hotel delivery.',
            ],
            [
                'name' => 'Battambang',
                'slug' => 'battambang',
                'state' => 'Battambang',
                'country' => 'Cambodia',
                'hero_image' => 'https://images.unsplash.com/photo-1473116763249-2faaef81ccda?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Relaxed city rides with roomy SUVs and stylish daily drivers.',
            ],
        ] as $city) {
            City::updateOrCreate(['slug' => $city['slug']], $city);
        }
    }
}
