<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Driveflow') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        @include('layouts.navigation')

        <main class="section-space">
            <div class="page-width">
                <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <div class="max-w-2xl">
                        <span class="eyebrow">Account access</span>
                        <h1 class="display-title mt-6">Sign in or register before booking a car.</h1>
                        <p class="section-copy mt-6 max-w-xl">
                            Customer accounts are used to save booking records and basic driver details.
                        </p>
                    </div>

                    <div class="shell-panel mx-auto w-full max-w-xl p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
