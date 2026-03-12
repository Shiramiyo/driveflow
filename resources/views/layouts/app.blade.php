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

        @if (session('status'))
            <div class="page-width mt-6">
                <div class="notice-banner">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @isset($header)
            <section class="page-width pt-10">
                <div class="shell-panel px-6 py-6 sm:px-8">
                    {{ $header }}
                </div>
            </section>
        @endisset

        <main class="pb-24">
            {{ $slot }}
        </main>

        <footer class="border-t border-white/10">
            <div class="page-width flex flex-col gap-4 py-8 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>Driveflow demo built in Laravel for a car rental marketplace workflow.</p>
                <p>Admin login: <span class="text-white">admin@driveflow.test</span> / <span class="text-white">password</span></p>
            </div>
        </footer>
    </body>
</html>
