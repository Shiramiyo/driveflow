<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $fillable = [
        'city_id',
        'host_id',
        'slug',
        'name',
        'brand',
        'model',
        'year',
        'car_type',
        'transmission',
        'seats',
        'fuel_type',
        'location_name',
        'price_per_day',
        'rating',
        'trips_count',
        'short_description',
        'description',
        'image_url',
        'gallery',
        'features',
        'pickup_options',
        'delivery_fee',
        'is_featured',
        'is_active',
        'instant_book',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'features' => 'array',
            'pickup_options' => 'array',
            'price_per_day' => 'decimal:2',
            'rating' => 'decimal:1',
            'delivery_fee' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'instant_book' => 'boolean',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
