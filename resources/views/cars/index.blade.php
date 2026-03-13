@extends('layouts.public')

@section('title', 'Browse Cars | Driveflow')

@section('content')
    <section class="section-space">
        <div class="page-width">
            <div class="mb-8">
                <h1 class="display-title text-3xl">Browse Cars</h1>
                <p class="section-copy mt-3 max-w-2xl">
                    Filter the car list by province, number of seats, and maximum daily price.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
                <aside class="shell-panel p-5">
                    <h2 class="text-xl font-semibold text-white">Filters</h2>

                    <form action="{{ route('cars.index') }}" method="GET" class="mt-5 space-y-4">
                        @if (request('start_at'))
                            <input type="hidden" name="start_at" value="{{ request('start_at') }}">
                        @endif

                        @if (request('end_at'))
                            <input type="hidden" name="end_at" value="{{ request('end_at') }}">
                        @endif

                        <div>
                            <label for="city" class="field-label">Province</label>
                            <select id="city" name="city" class="input-field">
                                <option value="">All provinces</option>
                                @foreach ($filterOptions['cities'] as $city)
                                    <option value="{{ $city->slug }}" @selected(request('city') === $city->slug)>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

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
                            <label for="max_price" class="field-label">Maximum price per day</label>
                            <input id="max_price" name="max_price" type="number" min="0" class="input-field" value="{{ request('max_price') }}" placeholder="150">
                        </div>

                        <div>
                            <label for="sort" class="field-label">Sort</label>
                            <select id="sort" name="sort" class="input-field">
                                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                                <option value="price_low" @selected(request('sort') === 'price_low')>Price low to high</option>
                                <option value="price_high" @selected(request('sort') === 'price_high')>Price high to low</option>
                            </select>
                        </div>

                        <div class="grid gap-3">
                            <button type="submit" class="button-primary w-full">Apply filters</button>
                            <a href="{{ route('cars.index') }}" class="button-secondary w-full text-center">Reset</a>
                        </div>
                    </form>
                </aside>

                <div class="space-y-5">
                    <div class="shell-panel p-5">
                        <p class="text-sm text-slate-300">
                            {{ $cars->total() }} cars found
                            @if (request('city'))
                                for {{ collect($filterOptions['cities'])->firstWhere('slug', request('city'))?->name ?? request('city') }}
                            @endif
                        </p>
                        <p class="mt-2 text-sm text-slate-400">
                            Trip dates:
                            {{ \Carbon\Carbon::parse(request('start_at', $defaultStartAt))->format('M d, Y H:i') }}
                            to
                            {{ \Carbon\Carbon::parse(request('end_at', $defaultEndAt))->format('M d, Y H:i') }}
                        </p>
                    </div>

                    @if ($cars->count())
                        <div class="grid gap-5 md:grid-cols-2">
                            @foreach ($cars as $car)
                                <article class="shell-panel overflow-hidden">
                                    <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-56 w-full object-cover">
                                    <div class="p-5">
                                        <p class="text-sm text-slate-400">{{ $car->city->name }} · {{ $car->brand }}</p>
                                        <h2 class="mt-2 text-xl font-semibold text-white">{{ $car->name }}</h2>
                                        <p class="mt-2 text-sm text-slate-300">{{ $car->short_description }}</p>

                                        <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-300">
                                            <span class="pill">{{ $car->seats }} seats</span>
                                            <span class="pill">{{ $car->transmission }}</span>
                                            <span class="pill">{{ $car->fuel_type }}</span>
                                        </div>

                                        <div class="mt-5 flex items-center justify-between">
                                            <p class="text-lg font-semibold text-white">${{ number_format((float) $car->price_per_day, 0) }}/day</p>
                                            <a
                                                href="{{ route('cars.show', ['car' => $car, 'start_at' => request('start_at', $defaultStartAt), 'end_at' => request('end_at', $defaultEndAt)]) }}"
                                                class="button-primary"
                                            >
                                                View details
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if ($cars->hasPages())
                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm text-slate-400">Page {{ $cars->currentPage() }} of {{ $cars->lastPage() }}</p>
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
                            <h2 class="text-2xl font-semibold text-white">No cars found.</h2>
                            <p class="mt-3 text-sm text-slate-300">Try using a different province or a higher maximum price.</p>
                            <a href="{{ route('cars.index') }}" class="button-primary mt-5">Reset filters</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
