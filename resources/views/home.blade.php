@extends('layouts.public')

@section('title', 'Driveflow | Car Rental System')

@section('content')
    <section class="section-space">
        <div class="page-width">
            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <span class="eyebrow">Laravel project</span>
                    <h1 class="display-title mt-4">Car rental website for browsing, booking, and simple admin management.</h1>
                    <p class="section-copy mt-4 max-w-2xl">
                        Driveflow lets customers search available cars by province, check the daily price, and place a booking online.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-300">
                        <span class="pill">Cars</span>
                        <span class="pill">Bookings</span>
                        <span class="pill">Customer records</span>
                        <span class="pill">Admin dashboard</span>
                    </div>
                </div>

                <div class="shell-panel p-6">
                    <h2 class="text-2xl font-semibold text-white">Search for a car</h2>

                    <form action="{{ route('cars.index') }}" method="GET" class="mt-6 space-y-4">
                        <div>
                            <label for="city" class="field-label">Province</label>
                            <select id="city" name="city" class="input-field">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->slug }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="start_at" class="field-label">Start date</label>
                                <input id="start_at" name="start_at" type="datetime-local" class="input-field" value="{{ $defaultStartAt }}">
                            </div>
                            <div>
                                <label for="end_at" class="field-label">End date</label>
                                <input id="end_at" name="end_at" type="datetime-local" class="input-field" value="{{ $defaultEndAt }}">
                            </div>
                        </div>

                        <button class="button-primary w-full" type="submit">Search cars</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="page-width">
            <div class="mb-6">
                <h2 class="section-title text-2xl">Supported provinces</h2>
                <p class="section-copy mt-2">The system currently uses four main locations from the database.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($cities as $city)
                    <div class="shell-panel p-5">
                        <h3 class="text-lg font-semibold text-white">{{ $city->name }}</h3>
                        <p class="mt-2 text-sm text-slate-400">{{ $city->cars_count }} car listings available</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="page-width">
            <div class="mb-6 flex items-end justify-between gap-4">
                <div>
                    <h2 class="section-title text-2xl">Available cars</h2>
                    <p class="section-copy mt-2">Customers can open a car, choose dates, and continue to checkout.</p>
                </div>
                <a href="{{ route('cars.index') }}" class="button-secondary">View all</a>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($featuredCars as $car)
                    <article class="shell-panel overflow-hidden">
                        <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-56 w-full object-cover">
                        <div class="p-5">
                            <p class="text-sm text-slate-400">{{ $car->city->name }}</p>
                            <h3 class="mt-2 text-xl font-semibold text-white">{{ $car->name }}</h3>
                            <p class="mt-2 text-sm text-slate-300">{{ $car->short_description }}</p>

                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-lg font-semibold text-white">${{ number_format((float) $car->price_per_day, 0) }}/day</p>
                                <a
                                    href="{{ route('cars.show', ['car' => $car, 'start_at' => $defaultStartAt, 'end_at' => $defaultEndAt]) }}"
                                    class="button-primary"
                                >
                                    View
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
