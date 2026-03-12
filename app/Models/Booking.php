<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'car_id',
        'city_id',
        'reference',
        'status',
        'booking_rate',
        'payment_method',
        'card_last_four',
        'payment_country',
        'pickup_option',
        'pickup_location',
        'dropoff_location',
        'start_at',
        'end_at',
        'trip_days',
        'price_per_day',
        'trip_subtotal',
        'protection_fee',
        'delivery_fee',
        'total_amount',
        'driver_phone',
        'driver_license_number',
        'notes',
        'wants_marketing',
        'terms_accepted',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'price_per_day' => 'decimal:2',
            'trip_subtotal' => 'decimal:2',
            'protection_fee' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'wants_marketing' => 'boolean',
            'terms_accepted' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
