<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Payment page</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Confirm your trip for {{ $car->name }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Driver details, booking rate, payment method, and live trip amount are all handled here.
                </p>
            </div>
            <a href="{{ route('cars.show', $car) }}" class="button-secondary">Back to car details</a>
        </div>
    </x-slot>

    <section class="page-width pt-10" x-data="checkoutQuote({
        rate: {{ (float) $car->price_per_day }},
        deliveryFee: {{ (float) $car->delivery_fee }},
        initialStartAt: '{{ old('start_at', $formDefaults['start_at']) }}',
        initialEndAt: '{{ old('end_at', $formDefaults['end_at']) }}',
        initialPickupOption: '{{ old('pickup_option', $formDefaults['pickup_option']) }}',
        initialBookingRate: '{{ old('booking_rate', $formDefaults['booking_rate']) }}'
    })">
        <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
            <form action="{{ route('bookings.store', $car) }}" method="POST" class="space-y-6">
                @csrf

                <div class="shell-panel p-7">
                    <h2 class="text-2xl font-semibold text-white">Trip details</h2>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="start_at" class="field-label">Start date & time</label>
                            <input id="start_at" name="start_at" type="datetime-local" class="input-field" x-model="startAt" @change="recalculate()" value="{{ old('start_at', $formDefaults['start_at']) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('start_at')" />
                        </div>
                        <div>
                            <label for="end_at" class="field-label">End date & time</label>
                            <input id="end_at" name="end_at" type="datetime-local" class="input-field" x-model="endAt" @change="recalculate()" value="{{ old('end_at', $formDefaults['end_at']) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('end_at')" />
                        </div>
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="pickup_option" class="field-label">Meeting / pickup option</label>
                            <select id="pickup_option" name="pickup_option" class="input-field" x-model="pickupOption" @change="recalculate()">
                                @foreach ($pickupOptionLabels as $value => $label)
                                    @if (in_array($value, $car->pickup_options, true))
                                        <option value="{{ $value }}" @selected(old('pickup_option', $formDefaults['pickup_option']) === $value)>{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('pickup_option')" />
                        </div>

                        <div>
                            <label for="payment_country" class="field-label">Country</label>
                            <input id="payment_country" name="payment_country" type="text" class="input-field" value="{{ old('payment_country', $formDefaults['payment_country']) }}" placeholder="Thailand">
                            <x-input-error class="mt-2" :messages="$errors->get('payment_country')" />
                        </div>
                    </div>
                </div>

                <div class="shell-panel p-7">
                    <h2 class="text-2xl font-semibold text-white">Primary driver</h2>
                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="driver_phone" class="field-label">Phone number</label>
                            <input id="driver_phone" name="driver_phone" type="text" class="input-field" value="{{ old('driver_phone', $formDefaults['driver_phone']) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('driver_phone')" />
                        </div>
                        <div>
                            <label for="driver_license_number" class="field-label">Driver license</label>
                            <input id="driver_license_number" name="driver_license_number" type="text" class="input-field" value="{{ old('driver_license_number', $formDefaults['driver_license_number']) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('driver_license_number')" />
                        </div>
                    </div>
                </div>

                <div class="shell-panel p-7">
                    <h2 class="text-2xl font-semibold text-white">Booking rate</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach ($bookingRates as $value => $label)
                            <label class="rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-200">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="booking_rate" value="{{ $value }}" class="mt-1 h-4 w-4 accent-lime-300" x-model="bookingRate" @change="recalculate()" @checked(old('booking_rate', $formDefaults['booking_rate']) === $value)>
                                    <div>
                                        <p class="font-semibold text-white">{{ $label }}</p>
                                        <p class="mt-2 leading-6 text-slate-400">
                                            {{ $value === 'refundable' ? 'Adds flexible cancellation protection to the trip total.' : 'Best price for confirmed dates with no refund after booking.' }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('booking_rate')" />
                </div>

                <div class="shell-panel p-7">
                    <h2 class="text-2xl font-semibold text-white">Payment method</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach ($paymentMethods as $value => $label)
                            <label class="rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-200">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="payment_method" value="{{ $value }}" class="mt-1 h-4 w-4 accent-lime-300" x-model="paymentMethod" @checked(old('payment_method', 'card') === $value)>
                                    <div>
                                        <p class="font-semibold text-white">{{ $label }}</p>
                                        <p class="mt-2 leading-6 text-slate-400">
                                            {{ $value === 'card' ? 'Enter card number, expiry date, and security code for the demo flow.' : 'Use ABA-style bank transfer details for a manual confirmation flow.' }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />

                    <div class="mt-6 grid gap-5 md:grid-cols-3" x-show="paymentMethod === 'card'" x-transition>
                        <div class="md:col-span-3">
                            <label for="card_number" class="field-label">Card number</label>
                            <input id="card_number" name="card_number" type="text" class="input-field" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242">
                            <x-input-error class="mt-2" :messages="$errors->get('card_number')" />
                        </div>
                        <div>
                            <label for="expiry_date" class="field-label">Expiration date</label>
                            <input id="expiry_date" name="expiry_date" type="text" class="input-field" value="{{ old('expiry_date') }}" placeholder="12/29">
                            <x-input-error class="mt-2" :messages="$errors->get('expiry_date')" />
                        </div>
                        <div>
                            <label for="security_code" class="field-label">Security code</label>
                            <input id="security_code" name="security_code" type="text" class="input-field" value="{{ old('security_code') }}" placeholder="123">
                            <x-input-error class="mt-2" :messages="$errors->get('security_code')" />
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-4 text-sm text-slate-400">
                            Demo only: the app stores only the last four digits after validation.
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="notes" class="field-label">Trip notes</label>
                        <textarea id="notes" name="notes" class="textarea-field" placeholder="Pickup reminders, extra requests, or itinerary notes">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="mt-6 space-y-3 text-sm text-slate-300">
                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <input type="checkbox" name="wants_marketing" value="1" class="h-4 w-4 accent-lime-300" @checked(old('wants_marketing'))>
                            <span>Send me promotions and announcements via email</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <input type="checkbox" name="terms_accepted" value="1" class="h-4 w-4 accent-lime-300" @checked(old('terms_accepted'))>
                            <span>I agree to the total shown and the booking terms for this demo app</span>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('terms_accepted')" />
                    </div>
                </div>

                <button type="submit" class="button-primary w-full py-4 text-base">Book trip</button>
            </form>

            <aside class="shell-panel sticky top-28 h-fit overflow-hidden">
                <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-64 w-full object-cover">
                <div class="p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">{{ $car->city->name }} · {{ $car->year }}</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">{{ $car->name }}</h2>
                        </div>
                        <span class="pill">{{ number_format((float) $car->rating, 1) }} ★</span>
                    </div>

                    <div class="mt-6 rounded-3xl border border-white/10 bg-slate-900/70 p-5 text-sm text-slate-300">
                        <div class="flex items-center justify-between">
                            <span>Trip amount</span>
                            <span x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Trip length</span>
                            <span x-text="`${tripDays} day${tripDays === 1 ? '' : 's'}`"></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Protection</span>
                            <span x-text="formatCurrency(protectionFee)"></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Meeting / delivery</span>
                            <span x-text="formatCurrency(deliveryTotal)"></span>
                        </div>
                        <div class="mt-4 border-t border-white/10 pt-4">
                            <div class="flex items-center justify-between text-base font-semibold text-white">
                                <span>Total amount</span>
                                <span x-text="formatCurrency(total)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3 text-sm text-slate-300">
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Meeting location</p>
                            <p class="mt-2 text-white">{{ $car->location_name }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Hosted by</p>
                            <p class="mt-2 text-white">{{ $car->host->name }}</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <script>
        window.checkoutQuote = ({ rate, deliveryFee, initialStartAt, initialEndAt, initialPickupOption, initialBookingRate }) => ({
            rate,
            deliveryFee,
            startAt: initialStartAt,
            endAt: initialEndAt,
            pickupOption: initialPickupOption,
            bookingRate: initialBookingRate,
            paymentMethod: '{{ old('payment_method', 'card') }}',
            tripDays: 1,
            subtotal: rate,
            protectionFee: 0,
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
                this.protectionFee = this.bookingRate === 'refundable' ? this.subtotal * 0.12 : 0;
                this.deliveryTotal = this.pickupOption === 'airport_meetup'
                    ? this.deliveryFee
                    : this.pickupOption === 'doorstep_delivery'
                        ? this.deliveryFee + 18
                        : 0;
                this.total = this.subtotal + this.protectionFee + this.deliveryTotal;
            },
            formatCurrency(value) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
            },
        });
    </script>
</x-app-layout>
