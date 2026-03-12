<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::withCount(['bookings', 'hostedCars'])
                ->orderBy('role')
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
