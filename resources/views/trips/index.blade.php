<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Trips</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Every booking you’ve made, in one place.</h1>
            </div>
            <a href="{{ route('cars.index') }}" class="button-primary">Book another car</a>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        @if ($bookings->count())
            <div class="grid gap-6 lg:grid-cols-2">
                @foreach ($bookings as $booking)
                    @php
                        $statusClass = match ($booking->status) {
                            'confirmed' => 'status-confirmed',
                            'pending' => 'status-pending',
                            'completed' => 'status-completed',
                            default => 'status-cancelled',
                        };
                    @endphp
                    <article class="shell-panel overflow-hidden">
                        <img src="{{ $booking->car->image_url }}" alt="{{ $booking->car->name }}" class="h-64 w-full object-cover">
                        <div class="p-7">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm text-slate-400">{{ $booking->car->city->name }} · {{ $booking->reference }}</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-white">{{ $booking->car->name }}</h2>
                                </div>
                                <span class="status-badge {{ $statusClass }}">{{ $booking->status }}</span>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                <div class="stat-tile">
                                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Start</p>
                                    <p class="mt-2 text-sm text-white">{{ $booking->start_at->format('M d, Y') }}</p>
                                </div>
                                <div class="stat-tile">
                                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Length</p>
                                    <p class="mt-2 text-sm text-white">{{ $booking->trip_days }} day{{ $booking->trip_days > 1 ? 's' : '' }}</p>
                                </div>
                                <div class="stat-tile">
                                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Total</p>
                                    <p class="mt-2 text-sm text-white">${{ number_format((float) $booking->total_amount, 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <p class="text-sm text-slate-400">Pickup at {{ $booking->pickup_location }}</p>
                                <a href="{{ route('trips.show', $booking) }}" class="button-secondary">View details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($bookings->hasPages())
                <div class="mt-6 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-400">Page {{ $bookings->currentPage() }} of {{ $bookings->lastPage() }}</div>
                    <div class="flex gap-3">
                        @if ($bookings->onFirstPage())
                            <span class="button-secondary opacity-40">Previous</span>
                        @else
                            <a href="{{ $bookings->previousPageUrl() }}" class="button-secondary">Previous</a>
                        @endif

                        @if ($bookings->hasMorePages())
                            <a href="{{ $bookings->nextPageUrl() }}" class="button-primary">Next</a>
                        @else
                            <span class="button-primary opacity-50">End</span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="shell-panel p-10 text-center">
                <h2 class="text-3xl font-semibold text-white">No trips yet.</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-slate-300">
                    Browse the marketplace, open a car detail page, and go through checkout to see your trip history appear here.
                </p>
                <a href="{{ route('cars.index') }}" class="button-primary mt-6">Browse cars</a>
            </div>
        @endif
    </section>
</x-app-layout>
