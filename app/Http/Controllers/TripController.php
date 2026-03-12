<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TripController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['car.city', 'car.host'])
            ->orderByDesc('start_at')
            ->paginate(8);

        return view('trips.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless(
            $request->user()->isAdmin() || $booking->user_id === $request->user()->id,
            Response::HTTP_FORBIDDEN,
        );

        return view('trips.show', [
            'booking' => $booking->load(['car.city', 'car.host', 'user']),
            'pickupOptionLabels' => [
                'self_pickup' => 'Self pickup',
                'airport_meetup' => 'Airport meetup',
                'doorstep_delivery' => 'Doorstep delivery',
            ],
        ]);
    }
}
