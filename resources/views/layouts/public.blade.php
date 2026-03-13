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
                    <p class="mt-1">Simple Laravel car rental system with customer booking and admin car management.</p>
                </div>
                <p class="text-left md:text-right">Final project demo</p>
            </div>
        </footer>
    </body>
</html>
