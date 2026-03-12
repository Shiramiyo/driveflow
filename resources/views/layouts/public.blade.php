<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Driveflow'))</title>

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

        <main class="pb-24">
            @yield('content')
        </main>

        <footer class="border-t border-white/10">
            <div class="page-width grid gap-5 py-8 text-sm text-slate-400 md:grid-cols-[1fr_auto] md:items-center">
                <div>
                    <p class="text-white">Driveflow</p>
                    <p class="mt-1">Turo-inspired Laravel marketplace demo with search, booking, trips, and admin tools.</p>
                </div>
                <div class="space-y-1 text-left md:text-right">
                    <p>Customer login: <span class="text-white">demo@driveflow.test</span> / <span class="text-white">password</span></p>
                    <p>Admin login: <span class="text-white">admin@driveflow.test</span> / <span class="text-white">password</span></p>
                </div>
            </div>
        </footer>
    </body>
</html>
