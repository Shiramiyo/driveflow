@extends('layouts.public')

@section('title', $car->name.' | Driveflow')

@section('content')
    @php
        $checkoutLabel = auth()->check() ? 'Continue to checkout' : 'Sign in to book';
    @endphp

    <section class="section-space" x-data="tripQuote({
        rate: {{ (float) $car->price_per_day }},
        deliveryFee: {{ (float) $car->delivery_fee }},
        startAt: '{{ $selectedStartAt }}',
        endAt: '{{ $selectedEndAt }}',
        pickupOption: '{{ $selectedPickupOption }}',
        checkoutBase: '{{ route('bookings.create', $car) }}'
    })">
        <div class="page-width">
            <div class="mb-8 flex flex-wrap items-center gap-3 text-sm text-slate-400">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span>/</span>
                <a href="{{ route('cars.index') }}" class="transition hover:text-white">Cars</a>
                <span>/</span>
                <span class="text-white">{{ $car->name }}</span>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="space-y-8">
                    <div class="grid gap-4 md:grid-cols-[1.4fr_0.6fr]">
                        <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-full min-h-[22rem] w-full rounded-[2rem] object-cover">
                        <div class="grid gap-4">
                            @foreach (collect($car->gallery)->slice(1, 2) as $galleryImage)
                                <img src="{{ $galleryImage }}" alt="{{ $car->name }} gallery" class="h-full min-h-[10.5rem] w-full rounded-[2rem] object-cover">
                            @endforeach
                        </div>
                    </div>

                    <div class="shell-panel p-7">
                        <span class="eyebrow">{{ $car->city->name }} · {{ $car->brand }}</span>
                        <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h1 class="display-title text-3xl sm:text-4xl">{{ $car->name }}</h1>
                                <p class="mt-3 text-base text-slate-300">{{ $car->short_description }}</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-4 text-right">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Hosted by</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $car->host->name }}</p>
                                <p class="text-sm text-lime-200">{{ number_format((float) $car->rating, 1) }} ★ · {{ $car->trips_count }} trips</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Year</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $car->year }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Transmission</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $car->transmission }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Fuel type</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $car->fuel_type }}</p>
                            </div>
                            <div class="stat-tile">
                                <p class="text-sm text-slate-400">Seats</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $car->seats }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-8 xl:grid-cols-[1fr_0.75fr]">
                            <div>
                                <h2 class="text-2xl font-semibold text-white">Description</h2>
                                <p class="mt-4 text-sm leading-7 text-slate-300">{{ $car->description }}</p>
                            </div>
                            <div>
                                <h2 class="text-2xl font-semibold text-white">Included features</h2>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach ($car->features as $feature)
                                        <span class="pill">{{ $feature }}</span>
                                    @endforeach
                                </div>

                                <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-5">
                                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Pickup options</p>
                                    <ul class="mt-4 space-y-3 text-sm text-slate-300">
                                        @foreach ($pickupOptionLabels as $value => $label)
                                            @if (in_array($value, $car->pickup_options, true))
                                                <li class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                                    <span>{{ $label }}</span>
                                                    <span class="text-lime-200">{{ $value === 'self_pickup' ? 'Included' : '+$'.number_format((float) $car->delivery_fee, 0) }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="shell-panel sticky top-28 h-fit p-7">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-slate-400">Price per day</p>
                            <p class="mt-2 text-4xl font-semibold text-white">${{ number_format((float) $car->price_per_day, 0) }}</p>
                        </div>
                        <span class="pill">{{ number_format((float) $car->rating, 1) }} ★</span>
                    </div>

                    <div class="mt-8 space-y-5">
                        <div>
                            <label for="detail-start" class="field-label">Start date & time</label>
                            <input id="detail-start" type="datetime-local" class="input-field" x-model="startAt" @change="recalculate()">
                        </div>

                        <div>
                            <label for="detail-end" class="field-label">End date & time</label>
                            <input id="detail-end" type="datetime-local" class="input-field" x-model="endAt" @change="recalculate()">
                        </div>

                        <div>
                            <label for="detail-pickup" class="field-label">Pickup option</label>
                            <select id="detail-pickup" class="input-field" x-model="pickupOption" @change="recalculate()">
                                @foreach ($pickupOptionLabels as $value => $label)
                                    @if (in_array($value, $car->pickup_options, true))
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 rounded-3xl border border-white/10 bg-slate-900/75 p-5">
                        <div class="flex items-center justify-between text-sm text-slate-300">
                            <span>Trip length</span>
                            <span x-text="`${tripDays} day${tripDays === 1 ? '' : 's'}`"></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm text-slate-300">
                            <span>Base fare</span>
                            <span x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm text-slate-300">
                            <span>Pickup / delivery</span>
                            <span x-text="formatCurrency(deliveryTotal)"></span>
                        </div>
                        <div class="mt-4 border-t border-white/10 pt-4">
                            <div class="flex items-center justify-between text-base font-semibold text-white">
                                <span>Estimated total</span>
                                <span x-text="formatCurrency(total)"></span>
                            </div>
                        </div>
                    </div>

                    <a :href="checkoutUrl" class="button-primary mt-8 w-full">
                        {{ $checkoutLabel }}
                    </a>

                    <p class="mt-4 text-sm leading-6 text-slate-400">
                        Automatic trip pricing updates as you change the dates. Final totals are rechecked server-side at checkout.
                    </p>
                </aside>
            </div>

            @if ($relatedCars->count())
                <div class="mt-16">
                    <div class="mb-6 flex items-end justify-between gap-4">
                        <div>
                            <span class="eyebrow">More in {{ $car->city->name }}</span>
                            <h2 class="section-title mt-4 text-2xl">Nearby alternatives</h2>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach ($relatedCars as $relatedCar)
                            <a href="{{ route('cars.show', $relatedCar) }}" class="shell-panel overflow-hidden transition duration-200 hover:-translate-y-1">
                                <img src="{{ $relatedCar->image_url }}" alt="{{ $relatedCar->name }}" class="h-56 w-full object-cover">
                                <div class="p-5">
                                    <p class="text-sm text-slate-400">{{ $relatedCar->brand }}</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $relatedCar->name }}</p>
                                    <p class="mt-3 text-sm text-lime-200">${{ number_format((float) $relatedCar->price_per_day, 0) }}/day</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        window.tripQuote = ({ rate, deliveryFee, startAt, endAt, pickupOption, checkoutBase }) => ({
            rate,
            deliveryFee,
            startAt,
            endAt,
            pickupOption,
            checkoutBase,
            tripDays: 1,
            subtotal: rate,
            deliveryTotal: 0,
            total: rate,
            init() {
                this.recalculate();
            },
            recalculate() {
                const start = new Date(this.startAt);
                const end = new Date(this.endAt);
                let diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

                if (!Number.isFinite(diff) || diff < 1) {
                    diff = 1;
                }

                this.tripDays = diff;
                this.subtotal = this.rate * diff;
                this.deliveryTotal = this.pickupOption === 'airport_meetup'
                    ? this.deliveryFee
                    : this.pickupOption === 'doorstep_delivery'
                        ? this.deliveryFee + 18
                        : 0;
                this.total = this.subtotal + this.deliveryTotal;
            },
            formatCurrency(value) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
            },
            get checkoutUrl() {
                const params = new URLSearchParams({
                    start_at: this.startAt,
                    end_at: this.endAt,
                    pickup_option: this.pickupOption,
                });

                return `${this.checkoutBase}?${params.toString()}`;
            },
        });
    </script>
@endsection
