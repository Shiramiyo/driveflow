@extends('layouts.public')

@section('title', 'Browse Cars | Driveflow')

@section('content')
    <section class="section-space">
        <div class="page-width">
            <div class="mb-10 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="eyebrow">Car listings</span>
                    <h1 class="display-title mt-5 text-3xl sm:text-4xl">Search and filter available cars.</h1>
                    <p class="section-copy mt-4">
                        Use the filters to narrow the listings by province, price, car type, fuel type, seats, and pickup option.
                    </p>
                </div>
                <a href="{{ route('home') }}" class="button-secondary">Back to home</a>
            </div>

            <div class="grid gap-8 xl:grid-cols-[320px_1fr]">
                <aside class="shell-panel p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Filters</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">Find a car</h2>
                        </div>
                        <a href="{{ route('cars.index') }}" class="text-sm font-semibold text-lime-300">Reset</a>
                    </div>

                    <form action="{{ route('cars.index') }}" method="GET" class="space-y-6">
                        <div>
                            <label for="city" class="field-label">Province</label>
                            <select id="city" name="city" class="input-field">
                                <option value="">Any province</option>
                                @foreach ($filterOptions['cities'] as $city)
                                    <option value="{{ $city->slug }}" @selected(request('city') === $city->slug)>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                            <div>
                                <label for="start_at" class="field-label">Start</label>
                                <input id="start_at" name="start_at" type="datetime-local" class="input-field" value="{{ $defaultStartAt }}">
                            </div>
                            <div>
                                <label for="end_at" class="field-label">End</label>
                                <input id="end_at" name="end_at" type="datetime-local" class="input-field" value="{{ $defaultEndAt }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="min_price" class="field-label">Min price</label>
                                <input id="min_price" name="min_price" type="number" min="0" class="input-field" value="{{ request('min_price') }}" placeholder="0">
                            </div>
                            <div>
                                <label for="max_price" class="field-label">Max price</label>
                                <input id="max_price" name="max_price" type="number" min="0" class="input-field" value="{{ request('max_price') }}" placeholder="500">
                            </div>
                        </div>

                        <div>
                            <label for="sort" class="field-label">Sort</label>
                            <select id="sort" name="sort" class="input-field">
                                <option value="featured" @selected(request('sort', 'featured') === 'featured')>Featured first</option>
                                <option value="price_low" @selected(request('sort') === 'price_low')>Lowest price</option>
                                <option value="price_high" @selected(request('sort') === 'price_high')>Highest price</option>
                                <option value="newest" @selected(request('sort') === 'newest')>Newest model year</option>
                            </select>
                        </div>

                        <div>
                            <p class="field-label">Brand</p>
                            <div class="space-y-2">
                                @foreach ($filterOptions['brands'] as $brand)
                                    <label class="flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/80">
                                        <input type="checkbox" name="brand[]" value="{{ $brand }}" class="h-4 w-4 accent-lime-300" @checked(in_array($brand, (array) request('brand', []), true))>
                                        <span>{{ $brand }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="field-label">Car type</p>
                            <div class="space-y-2">
                                @foreach ($filterOptions['carTypes'] as $type)
                                    <label class="flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/80">
                                        <input type="checkbox" name="car_type[]" value="{{ $type }}" class="h-4 w-4 accent-lime-300" @checked(in_array($type, (array) request('car_type', []), true))>
                                        <span>{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                            <div>
                                <label for="seats" class="field-label">Seats</label>
                                <select id="seats" name="seats" class="input-field">
                                    <option value="">Any</option>
                                    @foreach ([4, 5, 7] as $seatCount)
                                        <option value="{{ $seatCount }}" @selected((string) request('seats') === (string) $seatCount)>{{ $seatCount }}+</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="pickup_option" class="field-label">Pickup option</label>
                                <select id="pickup_option" name="pickup_option" class="input-field">
                                    <option value="">Any</option>
                                    @foreach ($filterOptions['pickupOptions'] as $value => $label)
                                        <option value="{{ $value }}" @selected(request('pickup_option') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <p class="field-label">Fuel type</p>
                            <div class="space-y-2">
                                @foreach ($filterOptions['fuelTypes'] as $fuelType)
                                    <label class="flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/80">
                                        <input type="checkbox" name="fuel_type[]" value="{{ $fuelType }}" class="h-4 w-4 accent-lime-300" @checked(in_array($fuelType, (array) request('fuel_type', []), true))>
                                        <span>{{ $fuelType }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="field-label">Transmission</p>
                            <div class="space-y-2">
                                @foreach ($filterOptions['transmissions'] as $transmission)
                                    <label class="flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/80">
                                        <input type="checkbox" name="transmission[]" value="{{ $transmission }}" class="h-4 w-4 accent-lime-300" @checked(in_array($transmission, (array) request('transmission', []), true))>
                                        <span>{{ $transmission }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="button-primary w-full">Apply filters</button>
                    </form>
                </aside>

                <div class="space-y-6">
                    <div class="flex flex-col gap-4 rounded-xl border border-white/10 bg-white/5 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-400">{{ $cars->total() }} cars found</p>
                            <p class="mt-1 text-lg font-semibold text-white">
                                Dates: {{ \Carbon\Carbon::parse($defaultStartAt)->format('M d, H:i') }} to {{ \Carbon\Carbon::parse($defaultEndAt)->format('M d, H:i') }}
                            </p>
                        </div>
                        <div class="pill">MySQL-backed filters</div>
                    </div>

                    @if ($cars->count())
                        <div class="grid gap-6 lg:grid-cols-2">
                            @foreach ($cars as $car)
                                <article class="shell-panel overflow-hidden">
                                    <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-64 w-full object-cover">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm text-slate-400">{{ $car->city->name }} · {{ $car->location_name }}</p>
                                                <h2 class="mt-2 text-2xl font-semibold text-white">{{ $car->name }}</h2>
                                            </div>
                                            <span class="pill">{{ number_format((float) $car->rating, 1) }} ★</span>
                                        </div>

                                        <p class="mt-4 text-sm leading-6 text-slate-300">{{ $car->short_description }}</p>

                                        <div class="mt-5 flex flex-wrap gap-2">
                                            <span class="pill">{{ $car->brand }}</span>
                                            <span class="pill">{{ $car->fuel_type }}</span>
                                            <span class="pill">{{ $car->seats }} seats</span>
                                        </div>

                                        <div class="mt-6 flex items-center justify-between">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Price per day</p>
                                                <p class="mt-1 text-2xl font-semibold text-white">${{ number_format((float) $car->price_per_day, 0) }}</p>
                                            </div>
                                            <a href="{{ route('cars.show', ['car' => $car, 'start_at' => $defaultStartAt, 'end_at' => $defaultEndAt, 'pickup_option' => request('pickup_option')]) }}" class="button-primary">
                                                View details
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if ($cars->hasPages())
                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 p-4">
                                <div class="text-sm text-slate-400">
                                    Page {{ $cars->currentPage() }} of {{ $cars->lastPage() }}
                                </div>
                                <div class="flex gap-3">
                                    @if ($cars->onFirstPage())
                                        <span class="button-secondary opacity-40">Previous</span>
                                    @else
                                        <a href="{{ $cars->previousPageUrl() }}" class="button-secondary">Previous</a>
                                    @endif

                                    @if ($cars->hasMorePages())
                                        <a href="{{ $cars->nextPageUrl() }}" class="button-primary">Next</a>
                                    @else
                                        <span class="button-primary opacity-50">End</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="shell-panel p-10 text-center">
                            <h2 class="text-2xl font-semibold text-white">No cars matched those filters.</h2>
                            <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-slate-300">
                                Try widening the price range, removing a few filters, or switching to another province.
                            </p>
                            <a href="{{ route('cars.index') }}" class="button-primary mt-6">Reset filters</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
