<?php

namespace App\Support;

use App\Models\Car;
use Carbon\Carbon;

class TripPriceCalculator
{
    public function quote(
        Car $car,
        string $startAt,
        string $endAt,
        string $pickupOption = 'self_pickup',
        string $bookingRate = 'non_refundable',
    ): array {
        $start = Carbon::parse($startAt);
        $end = Carbon::parse($endAt);

        if ($end->lessThanOrEqualTo($start)) {
            $end = $start->copy()->addDay();
        }

        $tripDays = max(1, (int) ceil($start->diffInMinutes($end) / 1440));
        $tripSubtotal = $tripDays * (float) $car->price_per_day;
        $protectionFee = $bookingRate === 'refundable'
            ? round($tripSubtotal * 0.12, 2)
            : 0;

        $deliveryFee = match ($pickupOption) {
            'airport_meetup' => (float) $car->delivery_fee,
            'doorstep_delivery' => (float) $car->delivery_fee + 18,
            default => 0,
        };

        return [
            'start_at' => $start,
            'end_at' => $end,
            'trip_days' => $tripDays,
            'price_per_day' => (float) $car->price_per_day,
            'trip_subtotal' => $tripSubtotal,
            'protection_fee' => $protectionFee,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $tripSubtotal + $protectionFee + $deliveryFee,
        ];
    }
}
