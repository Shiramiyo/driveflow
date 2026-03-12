<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Customers</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Manage customer profiles synced from accounts and bookings.</h1>
            </div>
            <form action="{{ route('admin.customers.index') }}" method="GET" class="grid gap-3 md:grid-cols-[minmax(220px,1fr)_220px_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email, phone..."
                    class="input-field"
                >
                <select name="city" class="input-field">
                    <option value="">All provinces</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" @selected((string) request('city') === (string) $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="button-secondary">Filter</button>
            </form>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="shell-panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Customer</th>
                            <th class="px-6 py-4 font-medium">Province</th>
                            <th class="px-6 py-4 font-medium">Linked account</th>
                            <th class="px-6 py-4 font-medium">Bookings</th>
                            <th class="px-6 py-4 font-medium">Address</th>
                            <th class="px-6 py-4 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($customers as $customer)
                            <tr class="text-slate-200">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">{{ $customer->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $customer->email ?: 'No email recorded' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $customer->phone ?: 'No phone recorded' }}</p>
                                </td>
                                <td class="px-6 py-4">{{ $customer->city?->name ?: 'Not set' }}</td>
                                <td class="px-6 py-4">
                                    @if ($customer->user)
                                        <span class="status-badge status-confirmed">{{ $customer->user->role }}</span>
                                    @else
                                        <span class="status-badge status-pending">standalone</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $customer->bookings_count }}</td>
                                <td class="px-6 py-4 max-w-xs text-slate-300">{{ $customer->address ?: 'No address recorded' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="button-secondary">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">No customer profiles match the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($customers->hasPages())
            <div class="mt-6 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm text-slate-400">Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}</div>
                <div class="flex gap-3">
                    @if ($customers->onFirstPage())
                        <span class="button-secondary opacity-40">Previous</span>
                    @else
                        <a href="{{ $customers->previousPageUrl() }}" class="button-secondary">Previous</a>
                    @endif

                    @if ($customers->hasMorePages())
                        <a href="{{ $customers->nextPageUrl() }}" class="button-primary">Next</a>
                    @else
                        <span class="button-primary opacity-50">End</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</x-app-layout>
