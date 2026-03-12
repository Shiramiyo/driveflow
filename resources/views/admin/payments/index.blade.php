<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Admin · Payments</p>
            <h1 class="mt-2 text-3xl font-semibold text-white">Review payment method and trip totals.</h1>
        </div>
    </x-slot>

    <section class="page-width pt-10">
        <div class="shell-panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Reference</th>
                            <th class="px-6 py-4 font-medium">Customer</th>
                            <th class="px-6 py-4 font-medium">Method</th>
                            <th class="px-6 py-4 font-medium">Country</th>
                            <th class="px-6 py-4 font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach ($payments as $payment)
                            <tr class="text-slate-200">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">{{ $payment->reference }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $payment->car->name }}</p>
                                </td>
                                <td class="px-6 py-4">{{ $payment->user->name }}</td>
                                <td class="px-6 py-4">
                                    <p>{{ strtoupper($payment->payment_method) }}</p>
                                    @if ($payment->card_last_four)
                                        <p class="mt-1 text-xs text-slate-500">**** {{ $payment->card_last_four }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $payment->payment_country }}</td>
                                <td class="px-6 py-4">${{ number_format((float) $payment->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($payments->hasPages())
            <div class="mt-6 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm text-slate-400">Page {{ $payments->currentPage() }} of {{ $payments->lastPage() }}</div>
                <div class="flex gap-3">
                    @if ($payments->onFirstPage())
                        <span class="button-secondary opacity-40">Previous</span>
                    @else
                        <a href="{{ $payments->previousPageUrl() }}" class="button-secondary">Previous</a>
                    @endif

                    @if ($payments->hasMorePages())
                        <a href="{{ $payments->nextPageUrl() }}" class="button-primary">Next</a>
                    @else
                        <span class="button-primary opacity-50">End</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</x-app-layout>
