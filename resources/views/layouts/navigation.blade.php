<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/75 backdrop-blur-xl">
    <div class="page-width">
        <div class="flex items-center justify-between py-4">
            <a href="{{ route('home') }}">
                <x-application-logo class="shrink-0" />
            </a>

            <div class="hidden items-center gap-2 lg:flex">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                <a href="{{ route('cars.index') }}" class="nav-link {{ request()->routeIs('cars.*') ? 'nav-link-active' : '' }}">Browse Cars</a>

                @auth
                    <a href="{{ route('trips.index') }}" class="nav-link {{ request()->routeIs('trips.*') ? 'nav-link-active' : '' }}">Trips</a>
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">Profile</a>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">Admin</a>
                    @endif
                @endif
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                @auth
                    <div class="pill">
                        <span class="h-2 w-2 rounded-full bg-lime-300"></span>
                        {{ auth()->user()->name }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="button-secondary" type="submit">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="button-ghost">Sign in</a>
                    <a href="{{ route('register') }}" class="button-primary">Create account</a>
                @endauth
            </div>

            <button @click="open = ! open" class="rounded-full border border-white/10 bg-white/5 p-3 text-white lg:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-transition class="space-y-3 border-t border-white/10 pb-5 pt-4 lg:hidden">
            <a href="{{ route('home') }}" class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-white">Home</a>
            <a href="{{ route('cars.index') }}" class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-white">Browse Cars</a>

            @auth
                <a href="{{ route('trips.index') }}" class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-white">Trips</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-white">Profile</a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-white">Admin</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block w-full rounded-2xl bg-white/5 px-4 py-3 text-left text-sm text-white" type="submit">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-white">Sign in</a>
                <a href="{{ route('register') }}" class="block rounded-2xl bg-lime-300 px-4 py-3 text-sm font-semibold text-slate-950">Create account</a>
            @endauth
        </div>
    </div>
</nav>
