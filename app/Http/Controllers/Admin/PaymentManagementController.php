<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class PaymentManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.payments.index', [
            'payments' => Booking::with(['car.city', 'user'])
                ->orderByDesc('created_at')
                ->paginate(12),
        ]);
    }
}
