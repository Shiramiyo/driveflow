@extends('layouts.public')

@section('title', 'Driveflow | Rent standout cars your way')

@section('content')
    <section class="section-space">
        <div class="page-width">
            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                <div class="max-w-3xl">
                    <span class="eyebrow">Inspired by Turo</span>
                    <h1 class="display-title mt-6">Rent bold cars with a polished Laravel booking flow.</h1>
                    <p class="section-copy mt-6 max-w-2xl">
                        Browse by city, choose your trip dates, compare premium listings, and book with profile-backed checkout and admin management behind the scenes.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <span class="pill">Airport meetup</span>
                        <span class="pill">Automatic pricing</span>
                        <span class="pill">Trips dashboard</span>
                        <span class="pill">Admin tools</span>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="stat-tile">
                            <p class="text-3xl font-semibold text-white">{{ $featuredCars->count() }}+</p>
                            <p class="mt-2 text-sm text-slate-400">Featured rentals</p>
                        </div>
                        <div class="stat-tile">
                            <p class="text-3xl font-semibold text-white">{{ $cities->count() }}</p>
                            <p class="mt-2 text-sm text-slate-400">Cities ready to search</p>
                        </div>
                        <div class="stat-tile">
                            <p class="text-3xl font-semibold text-white">24/7</p>
                            <p class="mt-2 text-sm text-slate-400">Booking flow availability</p>
                        </div>
                    </div>
                </div>

                <div class="shell-panel overflow-hidden p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-lime-200">Start your trip</p>
                            <h2 class="mt-3 text-2xl font-semibold text-white">Search the exact vibe you want.</h2>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-right">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Demo</p>
                            <p class="text-sm text-white">Customer and admin logins included</p>
                        </div>
                    </div>

                    <form action="{{ route('cars.index') }}" method="GET" class="mt-8 space-y-5">
                        <div>
                            <label for="city" class="field-label">Location</label>
                            <select id="city" name="city" class="input-field">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->slug }}">{{ $city->name }}, {{ $city->state }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="start_at" class="field-label">Trip Start</label>
                                <input id="start_at" name="start_at" type="datetime-local" class="input-field" value="{{ $defaultStartAt }}">
                            </div>
                            <div>
                                <label for="end_at" class="field-label">Trip End</label>
                                <input id="end_at" name="end_at" type="datetime-local" class="input-field" value="{{ $defaultEndAt }}">
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <button class="button-primary w-full" type="submit">Browse Cars</button>
                            <a href="{{ route('register') }}" class="button-secondary w-full">Create account first</a>
                        </div>
                    </form>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        @foreach ($recommendedCars as $car)
                            <a href="{{ route('cars.show', $car) }}" class="shell-panel-soft overflow-hidden transition duration-200 hover:-translate-y-1">
                                <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-36 w-full object-cover">
                                <div class="p-4">
                                    <p class="text-sm text-slate-400">{{ $car->city->name }}</p>
                                    <p class="mt-1 font-semibold text-white">{{ $car->name }}</p>
                                    <p class="mt-2 text-sm text-lime-200">${{ number_format((float) $car->price_per_day, 0) }}/day</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="page-width">
            <div class="mb-8 flex items-end justify-between gap-5">
                <div>
                    <span class="eyebrow">Featured cities</span>
                    <h2 class="section-title mt-4">Pick a city, then pick a mood.</h2>
                </div>
                <a href="{{ route('cars.index') }}" class="button-secondary">See all cars</a>
            </div>

            <div class="grid gap-5 lg:grid-cols-4">
                @foreach ($cities as $city)
                    <a href="{{ route('cars.index', ['city' => $city->slug]) }}" class="group shell-panel overflow-hidden">
                        <img src="{{ $city->hero_image }}" alt="{{ $city->name }}" class="h-56 w-full object-cover transition duration-500 group-hover:scale-105">
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-white">{{ $city->name }}</h3>
                                    <p class="mt-1 text-sm text-slate-400">{{ $city->cars_count }} active cars</p>
                                </div>
                                <span class="pill">Explore</span>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-slate-300">{{ $city->spotlight_copy }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="page-width">
            <div class="mb-8 flex items-end justify-between gap-5">
                <div>
                    <span class="eyebrow">Popular cars</span>
                    <h2 class="section-title mt-4">A marketplace homepage with real inventory behind it.</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-400">
                    Each card connects to detail pages, live pricing by trip length, and a profile-based checkout designed from your PDF brief.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($featuredCars as $car)
                    <article class="shell-panel overflow-hidden">
                        <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-64 w-full object-cover">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm text-slate-400">{{ $car->city->name }} · Hosted by {{ $car->host->name }}</p>
                                    <h3 class="mt-2 text-xl font-semibold text-white">{{ $car->name }}</h3>
                                </div>
                                <span class="pill">{{ number_format((float) $car->rating, 1) }} ★</span>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-slate-300">{{ $car->short_description }}</p>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <span class="pill">{{ $car->car_type }}</span>
                                <span class="pill">{{ $car->transmission }}</span>
                                <span class="pill">{{ $car->seats }} seats</span>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Price per day</p>
                                    <p class="mt-1 text-2xl font-semibold text-white">${{ number_format((float) $car->price_per_day, 0) }}</p>
                                </div>
                                <a href="{{ route('cars.show', ['car' => $car, 'start_at' => $defaultStartAt, 'end_at' => $defaultEndAt]) }}" class="button-primary">
                                    View Car
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
