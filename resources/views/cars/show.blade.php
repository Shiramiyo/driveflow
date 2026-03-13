@extends('layouts.public')

@section('title', $car->name.' | Driveflow')

@section('content')
    <section class="section-space">
        <div class="page-width">
            <div class="mb-6 text-sm text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('cars.index') }}" class="hover:text-white">Cars</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $car->name }}</span>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-6">
                    <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="w-full rounded-xl border border-white/10 object-cover shadow-sm">

                    <div class="shell-panel p-6">
                        <p class="text-sm text-slate-400">{{ $car->city->name }} · {{ $car->brand }}</p>
                        <h1 class="mt-2 text-3xl font-semibold text-white">{{ $car->name }}</h1>
                        <p class="mt-4 text-sm leading-7 text-slate-300">{{ $car->description }}</p>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Year</p>
                                <p class="mt-2 text-white">{{ $car->year }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Transmission</p>
                                <p class="mt-2 text-white">{{ $car->transmission }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Fuel</p>
                                <p class="mt-2 text-white">{{ $car->fuel_type }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Seats</p>
                                <p class="mt-2 text-white">{{ $car->seats }}</p>
                            </div>
                        </div>

                        @if (! empty($car->features))
                            <div class="mt-6">
                                <h2 class="text-xl font-semibold text-white">Features</h2>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach ($car->features as $feature)
                                        <span class="pill">{{ $feature }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <aside class="shell-panel p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Daily price</p>
                            <p class="mt-2 text-3xl font-semibold text-white">${{ number_format((float) $car->price_per_day, 0) }}/day</p>
                        </div>
                        <span class="pill">{{ number_format((float) $car->rating, 1) }} ★</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <label for="detail-start" class="field-label">Start date</label>
                            <input id="detail-start" type="datetime-local" class="input-field" value="{{ $selectedStartAt }}">
                        </div>

                        <div>
                            <label for="detail-end" class="field-label">End date</label>
                            <input id="detail-end" type="datetime-local" class="input-field" value="{{ $selectedEndAt }}">
                        </div>

                        <div>
                            <label for="detail-pickup" class="field-label">Pickup option</label>
                            <select id="detail-pickup" class="input-field">
                                @foreach ($pickupOptionLabels as $value => $label)
                                    @if (in_array($value, $car->pickup_options, true))
                                        <option value="{{ $value }}" @selected($selectedPickupOption === $value)>{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-white/10 bg-slate-900/70 p-5 text-sm text-slate-300">
                        <div class="flex items-center justify-between">
                            <span>Trip days</span>
                            <span id="quote-days">{{ $quote['trip_days'] }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Car rental</span>
                            <span id="quote-subtotal">${{ number_format((float) $quote['trip_subtotal'], 2) }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Pickup fee</span>
                            <span id="quote-delivery">${{ number_format((float) $quote['delivery_fee'], 2) }}</span>
                        </div>
                        <div class="mt-4 border-t border-white/10 pt-4">
                            <div class="flex items-center justify-between font-semibold text-white">
                                <span>Estimated total</span>
                                <span id="quote-total">${{ number_format((float) $quote['total_amount'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @auth
                        <button type="button" class="button-primary mt-6 w-full" onclick="goToCheckout()">Continue to checkout</button>
                    @else
                        <a href="{{ route('login') }}" class="button-primary mt-6 w-full">Sign in to continue</a>
                    @endauth
                </aside>
            </div>
        </div>
    </section>

    <script>
        const ratePerDay = {{ (float) $car->price_per_day }};
        const baseDeliveryFee = {{ (float) $car->delivery_fee }};
        const checkoutBaseUrl = @json(route('bookings.create', $car));

        function getTripDays() {
            const start = new Date(document.getElementById('detail-start').value);
            const end = new Date(document.getElementById('detail-end').value);
            const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

            return Number.isFinite(diff) && diff > 0 ? diff : 1;
        }

        function getPickupFee() {
            const pickupOption = document.getElementById('detail-pickup').value;

            if (pickupOption === 'airport_meetup') {
                return baseDeliveryFee;
            }

            if (pickupOption === 'doorstep_delivery') {
                return baseDeliveryFee + 18;
            }

            return 0;
        }

        function refreshQuote() {
            const tripDays = getTripDays();
            const subtotal = tripDays * ratePerDay;
            const pickupFee = getPickupFee();
            const total = subtotal + pickupFee;

            document.getElementById('quote-days').textContent = `${tripDays} day${tripDays === 1 ? '' : 's'}`;
            document.getElementById('quote-subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('quote-delivery').textContent = `$${pickupFee.toFixed(2)}`;
            document.getElementById('quote-total').textContent = `$${total.toFixed(2)}`;
        }

        function goToCheckout() {
            const params = new URLSearchParams({
                start_at: document.getElementById('detail-start').value,
                end_at: document.getElementById('detail-end').value,
                pickup_option: document.getElementById('detail-pickup').value,
            });

            window.location.href = `${checkoutBaseUrl}?${params.toString()}`;
        }

        document.getElementById('detail-start').addEventListener('change', refreshQuote);
        document.getElementById('detail-end').addEventListener('change', refreshQuote);
        document.getElementById('detail-pickup').addEventListener('change', refreshQuote);
        refreshQuote();
    </script>
@endsection
