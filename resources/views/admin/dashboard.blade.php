<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Admin dashboard</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Manage cars, bookings, and customers.</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.cars.create') }}" class="button-primary">Add car</a>
                <a href="{{ route('cars.index') }}" class="button-secondary">View website</a>
            </div>
        </div>
    </x-slot>

    <section class="page-width space-y-6 pt-10">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Cars</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $stats['cars'] }}</p>
            </div>
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Bookings</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $stats['bookings'] }}</p>
            </div>
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Customers</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $stats['customers'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="shell-panel p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Recent bookings</h2>
                        <p class="mt-1 text-sm text-slate-400">Latest customer bookings in the system.</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="button-secondary">View all</a>
                </div>

                <div class="space-y-4">
                    @forelse ($recentBookings as $booking)
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm text-slate-400">{{ $booking->reference }}</p>
                                    <p class="mt-1 font-semibold text-white">{{ $booking->car->name }}</p>
                                    <p class="mt-1 text-sm text-slate-300">{{ $booking->customer?->name ?? $booking->user->name }} · {{ $booking->car->city->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-300">${{ number_format((float) $booking->total_amount, 2) }}</p>
                                    <span class="status-badge {{ $booking->status === 'confirmed' ? 'status-confirmed' : ($booking->status === 'pending' ? 'status-pending' : ($booking->status === 'completed' ? 'status-completed' : 'status-cancelled')) }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">No bookings yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="shell-panel p-6">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">Recent cars</h2>
                            <p class="mt-1 text-sm text-slate-400">Cars added by the admin.</p>
                        </div>
                        <a href="{{ route('admin.cars.index') }}" class="button-secondary">Cars</a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($recentCars as $car)
                            <div class="flex items-center gap-4 rounded-xl border border-white/10 bg-white/5 p-4">
                                <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-16 w-20 rounded-lg object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-white">{{ $car->name }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $car->city->name }} · ${{ number_format((float) $car->price_per_day, 0) }}/day</p>
                                </div>
                                <a href="{{ route('admin.cars.edit', $car) }}" class="button-secondary">Edit</a>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">No cars available.</p>
                        @endforelse
                    </div>
                </div>

                <div class="shell-panel p-6">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">Recent customers</h2>
                            <p class="mt-1 text-sm text-slate-400">Customer records created from accounts and bookings.</p>
                        </div>
                        <a href="{{ route('admin.customers.index') }}" class="button-secondary">Customers</a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($recentCustomers as $customer)
                            <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                                <p class="font-semibold text-white">{{ $customer->name }}</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $customer->email ?: 'No email' }}</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $customer->city?->name ?: 'No province set' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">No customer records yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
