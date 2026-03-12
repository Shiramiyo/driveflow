<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin dashboard</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Manage inventory, bookings, users, and payments.</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.cars.create') }}" class="button-primary">Add car</a>
                <a href="{{ route('cars.index') }}" class="button-secondary">View marketplace</a>
            </div>
        </div>
    </x-slot>

    <section class="page-width space-y-8 pt-10">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Active cars</p>
                <p class="mt-3 text-4xl font-semibold text-white">{{ $stats['cars'] }}</p>
            </div>
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Bookings</p>
                <p class="mt-3 text-4xl font-semibold text-white">{{ $stats['bookings'] }}</p>
            </div>
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Users</p>
                <p class="mt-3 text-4xl font-semibold text-white">{{ $stats['users'] }}</p>
            </div>
            <div class="stat-tile">
                <p class="text-sm text-slate-400">Revenue captured</p>
                <p class="mt-3 text-4xl font-semibold text-white">${{ number_format((float) $stats['revenue'], 2) }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="shell-panel p-7">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Recent bookings</p>
                        <h2 class="mt-2 text-2xl font-semibold text-white">Latest trip activity</h2>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="button-secondary">Manage bookings</a>
                </div>

                <div class="space-y-4">
                    @foreach ($recentBookings as $booking)
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm text-slate-400">{{ $booking->reference }} · {{ $booking->user->name }}</p>
                                    <p class="mt-1 text-lg font-semibold text-white">{{ $booking->car->name }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $booking->car->city->name }} · {{ $booking->start_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-400">${{ number_format((float) $booking->total_amount, 2) }}</p>
                                    <span class="status-badge {{ $booking->status === 'confirmed' ? 'status-confirmed' : ($booking->status === 'pending' ? 'status-pending' : ($booking->status === 'completed' ? 'status-completed' : 'status-cancelled')) }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-8">
                <div class="shell-panel p-7">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Cars</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">Newest listings</h2>
                        </div>
                        <a href="{{ route('admin.cars.index') }}" class="button-secondary">All cars</a>
                    </div>

                    <div class="space-y-4">
                        @foreach ($recentCars as $car)
                            <div class="flex items-center gap-4 rounded-3xl border border-white/10 bg-white/5 p-4">
                                <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-20 w-24 rounded-2xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-white">{{ $car->name }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $car->city->name }} · Host: {{ $car->host->name }}</p>
                                </div>
                                <a href="{{ route('admin.cars.edit', $car) }}" class="button-secondary">Edit</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="shell-panel p-7">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Quick links</p>
                    <div class="mt-5 grid gap-3">
                        <a href="{{ route('admin.users.index') }}" class="rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-sm text-white transition hover:bg-white/10">Manage users</a>
                        <a href="{{ route('admin.payments.index') }}" class="rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-sm text-white transition hover:bg-white/10">Review payments</a>
                        <a href="{{ route('trips.index') }}" class="rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-sm text-white transition hover:bg-white/10">Open trip dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
