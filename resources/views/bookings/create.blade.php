<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Checkout</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Confirm booking for {{ $car->name }}</h1>
                <p class="mt-2 text-sm text-slate-300">Fill in the driver details and complete the payment section.</p>
            </div>
            <a href="{{ route('cars.show', $car) }}" class="button-secondary">Back to car</a>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <form action="{{ route('bookings.store', $car) }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="start_at" value="{{ $formDefaults['start_at'] }}">
                <input type="hidden" name="end_at" value="{{ $formDefaults['end_at'] }}">
                <input type="hidden" name="pickup_option" value="{{ $formDefaults['pickup_option'] }}">

                <div class="shell-panel p-6">
                    <h2 class="text-2xl font-semibold text-white">Trip summary</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        <div class="stat-tile">
                            <p class="text-sm text-slate-400">Start</p>
                            <p class="mt-2 text-white">{{ \Carbon\Carbon::parse($formDefaults['start_at'])->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="stat-tile">
                            <p class="text-sm text-slate-400">End</p>
                            <p class="mt-2 text-white">{{ \Carbon\Carbon::parse($formDefaults['end_at'])->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="stat-tile">
                            <p class="text-sm text-slate-400">Pickup option</p>
                            <p class="mt-2 text-white">{{ $pickupOptionLabels[$formDefaults['pickup_option']] ?? $formDefaults['pickup_option'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="shell-panel p-6">
                    <h2 class="text-2xl font-semibold text-white">Driver information</h2>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="driver_phone" class="field-label">Phone number</label>
                            <input id="driver_phone" name="driver_phone" type="text" class="input-field" value="{{ old('driver_phone', $formDefaults['driver_phone']) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('driver_phone')" />
                        </div>

                        <div>
                            <label for="driver_license_number" class="field-label">Driver license number</label>
                            <input id="driver_license_number" name="driver_license_number" type="text" class="input-field" value="{{ old('driver_license_number', $formDefaults['driver_license_number']) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('driver_license_number')" />
                        </div>
                    </div>
                </div>

                <div class="shell-panel p-6">
                    <h2 class="text-2xl font-semibold text-white">Payment</h2>

                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="payment_method" class="field-label">Payment method</label>
                            <select id="payment_method" name="payment_method" class="input-field" onchange="togglePaymentFields()">
                                @foreach ($paymentMethods as $value => $label)
                                    <option value="{{ $value }}" @selected(old('payment_method', $formDefaults['payment_method']) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
                        </div>

                        <div id="card-fields" class="space-y-5">
                            <div>
                                <label for="card_number" class="field-label">Card number</label>
                                <input id="card_number" name="card_number" type="text" class="input-field" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242">
                                <x-input-error class="mt-2" :messages="$errors->get('card_number')" />
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label for="expiry_date" class="field-label">Expiration date</label>
                                    <input id="expiry_date" name="expiry_date" type="text" class="input-field" value="{{ old('expiry_date') }}" placeholder="12/29">
                                    <x-input-error class="mt-2" :messages="$errors->get('expiry_date')" />
                                </div>

                                <div>
                                    <label for="security_code" class="field-label">CVC code</label>
                                    <input id="security_code" name="security_code" type="text" class="input-field" value="{{ old('security_code') }}" placeholder="123">
                                    <x-input-error class="mt-2" :messages="$errors->get('security_code')" />
                                </div>
                            </div>
                        </div>

                        <div id="aba-fields" class="hidden rounded-xl border border-white/10 bg-white/5 p-5">
                            <p class="text-sm font-medium text-white">ABA QR Payment</p>
                            <p class="mt-2 text-sm text-slate-300">Scan this QR code using ABA Mobile to complete the payment.</p>
                            <img src="{{ asset('images/aba-qr.jpg') }}" alt="ABA QR code" class="mt-4 w-full rounded-lg border border-white/10 bg-white object-contain">
                        </div>

                        <label class="flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                            <input type="checkbox" name="terms_accepted" value="1" class="h-4 w-4 accent-lime-300" @checked(old('terms_accepted'))>
                            <span>I confirm the booking details and payment summary.</span>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('terms_accepted')" />
                    </div>
                </div>

                <button type="submit" class="button-primary w-full">Confirm booking</button>
            </form>

            <aside class="shell-panel h-fit overflow-hidden">
                <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-64 w-full object-cover">
                <div class="p-6">
                    <p class="text-sm text-slate-400">{{ $car->city->name }} · {{ $car->year }}</p>
                    <h2 class="mt-2 text-2xl font-semibold text-white">{{ $car->name }}</h2>

                    <div class="mt-6 rounded-xl border border-white/10 bg-slate-900/70 p-5 text-sm text-slate-300">
                        <div class="flex items-center justify-between">
                            <span>Trip days</span>
                            <span>{{ $quote['trip_days'] }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Rental fee</span>
                            <span>${{ number_format((float) $quote['trip_subtotal'], 2) }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span>Pickup fee</span>
                            <span>${{ number_format((float) $quote['delivery_fee'], 2) }}</span>
                        </div>
                        <div class="mt-4 border-t border-white/10 pt-4">
                            <div class="flex items-center justify-between text-base font-semibold text-white">
                                <span>Total amount</span>
                                <span>${{ number_format((float) $quote['total_amount'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <script>
        function togglePaymentFields() {
            const select = document.getElementById('payment_method');
            const cardFields = document.getElementById('card-fields');
            const abaFields = document.getElementById('aba-fields');
            const isCard = select.value === 'card';

            cardFields.classList.toggle('hidden', !isCard);
            abaFields.classList.toggle('hidden', isCard);
        }

        togglePaymentFields();
    </script>
</x-app-layout>
