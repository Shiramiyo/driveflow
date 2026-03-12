<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Cars</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Add and edit car details.</h1>
            </div>
            <a href="{{ route('admin.cars.create') }}" class="button-primary">Add new car</a>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="shell-panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Car</th>
                            <th class="px-6 py-4 font-medium">City</th>
                            <th class="px-6 py-4 font-medium">Host</th>
                            <th class="px-6 py-4 font-medium">Price/day</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach ($cars as $car)
                            <tr class="text-slate-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="h-16 w-20 rounded-2xl object-cover">
                                        <div>
                                            <p class="font-semibold text-white">{{ $car->name }}</p>
                                            <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">{{ $car->brand }} · {{ $car->year }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $car->city->name }}</td>
                                <td class="px-6 py-4">{{ $car->host->name }}</td>
                                <td class="px-6 py-4">${{ number_format((float) $car->price_per_day, 0) }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $car->is_active ? 'status-confirmed' : 'status-cancelled' }}">
                                        {{ $car->is_active ? 'active' : 'inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="button-secondary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($cars->hasPages())
            <div class="mt-6 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm text-slate-400">Page {{ $cars->currentPage() }} of {{ $cars->lastPage() }}</div>
                <div class="flex gap-3">
                    @if ($cars->onFirstPage())
                        <span class="button-secondary opacity-40">Previous</span>
                    @else
                        <a href="{{ $cars->previousPageUrl() }}" class="button-secondary">Previous</a>
                    @endif

                    @if ($cars->hasMorePages())
                        <a href="{{ $cars->nextPageUrl() }}" class="button-primary">Next</a>
                    @else
                        <span class="button-primary opacity-50">End</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</x-app-layout>
