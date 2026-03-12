<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Customers</p>
            <h1 class="mt-2 text-3xl font-semibold text-white">Edit customer profile</h1>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <form action="{{ $formAction }}" method="POST" class="shell-panel p-7">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="field-label">Full name</label>
                    <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $customer->name) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="email" class="field-label">Email address</label>
                    <input id="email" name="email" type="email" class="input-field" value="{{ old('email', $customer->email) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="phone" class="field-label">Phone number</label>
                    <input id="phone" name="phone" type="text" class="input-field" value="{{ old('phone', $customer->phone) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <div>
                    <label for="driver_license_number" class="field-label">Driver license number</label>
                    <input id="driver_license_number" name="driver_license_number" type="text" class="input-field" value="{{ old('driver_license_number', $customer->driver_license_number) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('driver_license_number')" />
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="city_id" class="field-label">Province / city</label>
                    <select id="city_id" name="city_id" class="input-field">
                        <option value="">Not set</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" @selected((string) old('city_id', $customer->city_id) === (string) $city->id)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('city_id')" />
                </div>

                <div>
                    <label for="address" class="field-label">Address</label>
                    <input id="address" name="address" type="text" class="input-field" value="{{ old('address', $customer->address) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
            </div>

            @if ($customer->user)
                <div class="mt-6 rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Linked login account</p>
                    <p class="mt-2 text-white">{{ $customer->user->name }} · {{ $customer->user->email }}</p>
                    <p class="mt-2 text-slate-400">Saving here also updates the linked user account details.</p>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                <button type="submit" class="button-primary">Save customer</button>
                <a href="{{ route('admin.customers.index') }}" class="button-secondary">Cancel</a>
            </div>
        </form>
    </section>
</x-app-layout>
