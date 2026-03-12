<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Users</p>
            <h1 class="mt-2 text-3xl font-semibold text-white">Manage users across customer, host, and admin roles.</h1>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="shell-panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Name</th>
                            <th class="px-6 py-4 font-medium">Role</th>
                            <th class="px-6 py-4 font-medium">Phone</th>
                            <th class="px-6 py-4 font-medium">Trips</th>
                            <th class="px-6 py-4 font-medium">Hosted cars</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach ($users as $user)
                            <tr class="text-slate-200">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $user->role === 'admin' ? 'status-confirmed' : ($user->role === 'host' ? 'status-completed' : 'status-pending') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $user->phone ?: 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $user->bookings_count }}</td>
                                <td class="px-6 py-4">{{ $user->hosted_cars_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="mt-6 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm text-slate-400">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</div>
                <div class="flex gap-3">
                    @if ($users->onFirstPage())
                        <span class="button-secondary opacity-40">Previous</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="button-secondary">Previous</a>
                    @endif

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="button-primary">Next</a>
                    @else
                        <span class="button-primary opacity-50">End</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</x-app-layout>
