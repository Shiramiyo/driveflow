<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerManagementController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::with(['city', 'user'])
            ->withCount('bookings')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->input('search'));

                $query->where(function ($nestedQuery) use ($term) {
                    $nestedQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('driver_license_number', 'like', "%{$term}%");
                });
            })
            ->when(
                $request->filled('city'),
                fn ($query) => $query->where('city_id', $request->integer('city'))
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
            'cities' => City::orderBy('name')->get(),
        ]);
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.form', [
            'customer' => $customer->load(['city', 'user']),
            'cities' => City::orderBy('name')->get(),
            'formAction' => route('admin.customers.update', $customer),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],
            'phone' => ['required', 'string', 'max:30'],
            'driver_license_number' => ['nullable', 'string', 'max:60'],
            'address' => ['nullable', 'string', 'max:255'],
            'city_id' => ['nullable', Rule::exists('cities', 'id')],
        ]);

        $payload = [
            ...$validated,
            'email' => $validated['email'] ?: $customer->user?->email,
        ];

        $customer->update($payload);

        if ($customer->user) {
            $customer->user->forceFill([
                'name' => $payload['name'],
                'email' => $payload['email'] ?: $customer->user->email,
                'phone' => $payload['phone'],
                'driver_license_number' => $payload['driver_license_number'] ?: $customer->user->driver_license_number,
            ])->save();
        }

        return redirect()
            ->route('admin.customers.index')
            ->with('status', 'Customer profile updated.');
    }
}
