<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Bookings</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">View bookings and update trip status.</h1>
            </div>
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex gap-3">
                <select name="status" class="input-field min-w-44">
                    <option value="">All statuses</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="button-secondary">Filter</button>
            </form>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="space-y-4">
            @foreach ($bookings as $booking)
                <div class="shell-panel p-6">
                    <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <p class="text-lg font-semibold text-white">{{ $booking->reference }}</p>
                                <span class="status-badge {{ $booking->status === 'confirmed' ? 'status-confirmed' : ($booking->status === 'pending' ? 'status-pending' : ($booking->status === 'completed' ? 'status-completed' : 'status-cancelled')) }}">
                                    {{ $booking->status }}
                                </span>
                            </div>
                            <p class="mt-3 text-sm text-slate-400">{{ $booking->customer?->name ?? $booking->user->name }} booked {{ $booking->car->name }} in {{ $booking->car->city->name }}</p>
                            <div class="mt-4 flex flex-wrap gap-3 text-sm text-slate-300">
                                <span class="pill">{{ $booking->start_at->format('M d, Y H:i') }}</span>
                                <span class="pill">{{ $booking->trip_days }} day{{ $booking->trip_days > 1 ? 's' : '' }}</span>
                                <span class="pill">${{ number_format((float) $booking->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="input-field min-w-44">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="button-primary">Save status</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($bookings->hasPages())
            <div class="mt-6 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm text-slate-400">Page {{ $bookings->currentPage() }} of {{ $bookings->lastPage() }}</div>
                <div class="flex gap-3">
                    @if ($bookings->onFirstPage())
                        <span class="button-secondary opacity-40">Previous</span>
                    @else
                        <a href="{{ $bookings->previousPageUrl() }}" class="button-secondary">Previous</a>
                    @endif

                    @if ($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}" class="button-primary">Next</a>
                    @else
                        <span class="button-primary opacity-50">End</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</x-app-layout>
