<x-admin-layout>
    <x-slot name="header">
        Payments Report
    </x-slot>

    <div class="space-y-6">
        
        <!-- Summary Widgets -->
        <div class="flex flex-nowrap items-stretch gap-4 overflow-x-auto pb-4 custom-scrollbar">
            <!-- Total Received -->
            <div class="relative overflow-hidden rounded-md shadow-md group min-w-[300px] flex-1" style="background-color: #28a745;">
                <div class="p-5 h-full flex flex-col justify-between">
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-white tracking-tight">PKR {{ number_format($totalReceived, 0) }}</span>
                        <span class="text-sm font-normal text-white/90 mt-1 uppercase tracking-wide">Total Received (Filtered)</span>
                    </div>
                    <div class="absolute right-3 top-4 opacity-20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 1.9 1.55 3.28 3.66 3.75V21h3v-2.15c1.87-.37 3.5-1.5 3.5-3.55 0-2.79-2.39-3.7-4.99-4.4z"/></svg>
                    </div>
                    <div class="mt-4 py-1 bg-black/10 text-center text-[10px] text-white uppercase font-bold tracking-widest rounded">
                        Total Collections
                    </div>
                </div>
            </div>

            <!-- Monthly Collection -->
            <div class="relative overflow-hidden rounded-md shadow-md group min-w-[300px] flex-1" style="background-color: #17a2b8;">
                <div class="p-5 h-full flex flex-col justify-between">
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-white tracking-tight">PKR {{ number_format($thisMonthCollection, 0) }}</span>
                        <span class="text-sm font-normal text-white/90 mt-1 uppercase tracking-wide">This Month's Collection</span>
                    </div>
                    <div class="absolute right-3 top-4 opacity-20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
                    </div>
                    <div class="mt-4 py-1 bg-black/10 text-center text-[10px] text-white uppercase font-bold tracking-widest rounded">
                        {{ now()->format('F Y') }} Performance
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <x-report-filter 
            :action="route('reports.payments')" 
            :showClient="true" 
            :clients="$clients" 
        />

        <!-- Print Header -->
        <div class="print-header hidden p-6 text-center border-b border-black mb-4">
            <h1 class="text-2xl font-black italic underline tracking-wider mb-1" style="font-family: serif;">SEA PEARL SERVICES</h1>
            <h2 class="text-lg font-bold uppercase mt-2">Payments Report</h2>
            <p class="text-sm">Printed on: {{ date('d/m/Y h:i A') }}</p>
            @if(request('start_date') || request('end_date'))
                <p class="text-sm font-bold mt-1 uppercase">
                    Period: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Start' }} 
                    to 
                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Today' }}
                </p>
            @endif
        </div>

        <!-- Report Data Table -->
        <div class="bg-white rounded shadow-md border-t-4 border-slate-600 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-widest border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Payment Date</th>
                            <th class="px-6 py-4">Client Name</th>
                            <th class="px-6 py-4">Invoice Reference</th>
                            <th class="px-6 py-4 text-right">Received Amount</th>
                            <th class="px-6 py-4 text-center">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700">{{ $payment->bill->client->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-indigo-600">
                                    @if($payment->bill)
                                    <a href="{{ route('bills.show', $payment->bill) }}" class="hover:underline">{{ $payment->bill->bill_no }}</a>
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-black text-emerald-600">{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded uppercase">{{ $payment->payment_method }}</span>
                                    @if($payment->reference_no)
                                        <div class="text-[9px] text-slate-400 mt-1 uppercase">{{ $payment->reference_no }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium italic">
                                    No payments found for the selected criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 print:hidden">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            aside, nav, header, form, .print\:hidden, .pagination { display: none !important; }
            body { background: white !important; margin: 0; padding: 0; }
            main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
            .bg-white { box-shadow: none !important; border: none !important; }
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #000 !important; color: #000 !important; padding: 8px !important; }
            th { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-header { display: block !important; }
            .border-t.border-slate-100 { border-top: none !important; }
            .grid-cols-1.md\:grid-cols-2 { display: flex !important; gap: 1rem !important; }
            .grid-cols-1.md\:grid-cols-2 > div { flex: 1 !important; border: 1px solid #000 !important; border-top: 4px solid #000 !important; }
        }
    </style>
</x-admin-layout>
