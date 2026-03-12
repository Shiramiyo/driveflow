<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Cars</p>
            <h1 class="mt-2 text-3xl font-semibold text-white">{{ $car->exists ? 'Edit car details' : 'Add a new car' }}</h1>
        </div>
    </x-slot>

    @php
        $selectedPickupOptions = old('pickup_options', $car->pickup_options ?? []);
        $galleryInput = old('gallery_input', $car->gallery ? implode(PHP_EOL, $car->gallery) : $car->image_url);
        $featuresInput = old('features_input', $car->features ? implode(PHP_EOL, $car->features) : '');
    @endphp

    <section class="page-width pt-10">
        <form action="{{ $formAction }}" method="POST" class="shell-panel p-7">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="city_id" class="field-label">City</label>
                    <select id="city_id" name="city_id" class="input-field">
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" @selected((string) old('city_id', $car->city_id) === (string) $city->id)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('city_id')" />
                </div>

                <div>
                    <label for="host_id" class="field-label">Host user</label>
                    <select id="host_id" name="host_id" class="input-field">
                        @foreach ($hosts as $host)
                            <option value="{{ $host->id }}" @selected((string) old('host_id', $car->host_id) === (string) $host->id)>{{ $host->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('host_id')" />
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="field-label">Car name</label>
                    <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $car->name) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <label for="image_url" class="field-label">Primary image URL</label>
                    <input id="image_url" name="image_url" type="url" class="input-field" value="{{ old('image_url', $car->image_url) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('image_url')" />
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-4">
                <div>
                    <label for="brand" class="field-label">Brand</label>
                    <input id="brand" name="brand" type="text" class="input-field" value="{{ old('brand', $car->brand) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('brand')" />
                </div>
                <div>
                    <label for="model" class="field-label">Model</label>
                    <input id="model" name="model" type="text" class="input-field" value="{{ old('model', $car->model) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('model')" />
                </div>
                <div>
                    <label for="year" class="field-label">Year</label>
                    <input id="year" name="year" type="number" class="input-field" value="{{ old('year', $car->year) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('year')" />
                </div>
                <div>
                    <label for="price_per_day" class="field-label">Price per day</label>
                    <input id="price_per_day" name="price_per_day" type="number" step="0.01" class="input-field" value="{{ old('price_per_day', $car->price_per_day) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('price_per_day')" />
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-4">
                <div>
                    <label for="car_type" class="field-label">Car type</label>
                    <input id="car_type" name="car_type" type="text" class="input-field" value="{{ old('car_type', $car->car_type) }}">
                </div>
                <div>
                    <label for="transmission" class="field-label">Transmission</label>
                    <input id="transmission" name="transmission" type="text" class="input-field" value="{{ old('transmission', $car->transmission) }}">
                </div>
                <div>
                    <label for="seats" class="field-label">Seats</label>
                    <input id="seats" name="seats" type="number" class="input-field" value="{{ old('seats', $car->seats) }}">
                </div>
                <div>
                    <label for="fuel_type" class="field-label">Fuel type</label>
                    <input id="fuel_type" name="fuel_type" type="text" class="input-field" value="{{ old('fuel_type', $car->fuel_type) }}">
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <div>
                    <label for="location_name" class="field-label">Pickup location</label>
                    <input id="location_name" name="location_name" type="text" class="input-field" value="{{ old('location_name', $car->location_name) }}">
                </div>
                <div>
                    <label for="rating" class="field-label">Rating</label>
                    <input id="rating" name="rating" type="number" step="0.1" class="input-field" value="{{ old('rating', $car->rating ?? 5) }}">
                </div>
                <div>
                    <label for="trips_count" class="field-label">Trips count</label>
                    <input id="trips_count" name="trips_count" type="number" class="input-field" value="{{ old('trips_count', $car->trips_count ?? 0) }}">
                </div>
            </div>

            <div class="mt-6">
                <label for="short_description" class="field-label">Short description</label>
                <input id="short_description" name="short_description" type="text" class="input-field" value="{{ old('short_description', $car->short_description) }}">
            </div>

            <div class="mt-6">
                <label for="description" class="field-label">Description</label>
                <textarea id="description" name="description" class="textarea-field">{{ old('description', $car->description) }}</textarea>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="gallery_input" class="field-label">Gallery URLs (one per line)</label>
                    <textarea id="gallery_input" name="gallery_input" class="textarea-field">{{ $galleryInput }}</textarea>
                </div>
                <div>
                    <label for="features_input" class="field-label">Features (one per line)</label>
                    <textarea id="features_input" name="features_input" class="textarea-field">{{ $featuresInput }}</textarea>
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <p class="field-label">Pickup options</p>
                    <div class="space-y-3">
                        @foreach ($pickupOptionChoices as $value => $label)
                            <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white">
                                <input type="checkbox" name="pickup_options[]" value="{{ $value }}" class="h-4 w-4 accent-lime-300" @checked(in_array($value, (array) $selectedPickupOptions, true))>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="delivery_fee" class="field-label">Delivery fee</label>
                        <input id="delivery_fee" name="delivery_fee" type="number" step="0.01" class="input-field" value="{{ old('delivery_fee', $car->delivery_fee ?? 0) }}">
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white">
                            <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 accent-lime-300" @checked(old('is_featured', $car->is_featured ?? false))>
                            <span>Featured</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white">
                            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 accent-lime-300" @checked(old('is_active', $car->exists ? $car->is_active : true))>
                            <span>Active</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white">
                            <input type="checkbox" name="instant_book" value="1" class="h-4 w-4 accent-lime-300" @checked(old('instant_book', $car->exists ? $car->instant_book : true))>
                            <span>Instant book</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <button type="submit" class="button-primary">{{ $car->exists ? 'Save changes' : 'Create car' }}</button>
                <a href="{{ route('admin.cars.index') }}" class="button-secondary">Cancel</a>
            </div>
        </form>
    </section>
</x-app-layout>
