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
                'name' => 'Bangkok',
                'slug' => 'bangkok',
                'state' => 'Bangkok',
                'country' => 'Thailand',
                'hero_image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Late-night city drives, rooftop pickups, and premium daily commuters.',
            ],
            [
                'name' => 'Chiang Mai',
                'slug' => 'chiang-mai',
                'state' => 'Chiang Mai',
                'country' => 'Thailand',
                'hero_image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Mountain weekends, cafe runs, and easy airport meetups.',
            ],
            [
                'name' => 'Phuket',
                'slug' => 'phuket',
                'state' => 'Phuket',
                'country' => 'Thailand',
                'hero_image' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Beach-ready convertibles, hybrid cruisers, and hotel delivery.',
            ],
            [
                'name' => 'Pattaya',
                'slug' => 'pattaya',
                'state' => 'Chonburi',
                'country' => 'Thailand',
                'hero_image' => 'https://images.unsplash.com/photo-1473116763249-2faaef81ccda?auto=format&fit=crop&w=1200&q=80',
                'spotlight_copy' => 'Quick coastal escapes with bold SUVs and stylish coupes.',
            ],
        ] as $city) {
            City::updateOrCreate(['slug' => $city['slug']], $city);
        }
    }
}
