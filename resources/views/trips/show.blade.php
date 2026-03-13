<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Booking details</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">{{ $booking->reference }}</h1>
                <p class="mt-2 text-sm text-slate-300">{{ $booking->car->name }} in {{ $booking->car->city->name }}</p>
            </div>
            <a href="{{ route('trips.index') }}" class="button-secondary">Back to trips</a>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-6">
                <div class="shell-panel overflow-hidden">
                    <img src="{{ $booking->car->image_url }}" alt="{{ $booking->car->name }}" class="h-72 w-full object-cover">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-white">{{ $booking->car->name }}</h2>
                                <p class="mt-2 text-sm text-slate-300">{{ $booking->pickup_location }}</p>
                            </div>
                            <span class="status-badge {{ $booking->status === 'confirmed' ? 'status-confirmed' : ($booking->status === 'pending' ? 'status-pending' : ($booking->status === 'completed' ? 'status-completed' : 'status-cancelled')) }}">
                                {{ $booking->status }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="stat-tile">
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Start</p>
                                <p class="mt-2 text-sm text-white">{{ $booking->start_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">End</p>
                                <p class="mt-2 text-sm text-white">{{ $booking->end_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Days</p>
                                <p class="mt-2 text-sm text-white">{{ $booking->trip_days }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Pickup</p>
                                <p class="mt-2 text-sm text-white">{{ $pickupOptionLabels[$booking->pickup_option] ?? $booking->pickup_option }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="shell-panel p-6">
                        <h2 class="text-xl font-semibold text-white">Driver</h2>
                        <div class="mt-4 space-y-3 text-sm text-slate-300">
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Name</p>
                                <p class="mt-1 text-white">{{ $booking->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Phone</p>
                                <p class="mt-1 text-white">{{ $booking->driver_phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">License</p>
                                <p class="mt-1 text-white">{{ $booking->driver_license_number }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="shell-panel p-6">
                        <h2 class="text-xl font-semibold text-white">Payment</h2>
                        <div class="mt-4 space-y-3 text-sm text-slate-300">
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Method</p>
                                <p class="mt-1 text-white">{{ $booking->payment_method === 'aba' ? 'ABA transfer' : 'Card' }}</p>
                            </div>
                            @if ($booking->card_last_four)
                                <div>
                                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Card</p>
                                    <p class="mt-1 text-white">Ending in {{ $booking->card_last_four }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Country</p>
                                <p class="mt-1 text-white">{{ $booking->payment_country }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="shell-panel h-fit p-6">
                <h2 class="text-xl font-semibold text-white">Payment summary</h2>

                <div class="mt-5 space-y-3 rounded-xl border border-white/10 bg-slate-900/70 p-5 text-sm text-slate-300">
                    <div class="flex items-center justify-between">
                        <span>Rental fee</span>
                        <span>${{ number_format((float) $booking->trip_subtotal, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Pickup fee</span>
                        <span>${{ number_format((float) $booking->delivery_fee, 2) }}</span>
                    </div>
                    <div class="border-t border-white/10 pt-4">
                        <div class="flex items-center justify-between font-semibold text-white">
                            <span>Total paid</span>
                            <span>${{ number_format((float) $booking->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-app-layout>
