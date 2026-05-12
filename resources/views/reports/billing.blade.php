<x-admin-layout>
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Billing Report</h1>
                <p class="text-sm text-slate-500">Overview of all generated bills and financial status</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-50 transition-all uppercase tracking-wider shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Report
                </button>
            </div>
        </div>
        
        <!-- Summary Widgets (Horizontal Layout) -->
        <div class="flex flex-nowrap items-stretch gap-4 overflow-x-auto pb-4 custom-scrollbar">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm group hover:shadow-md transition-all border-l-4 border-l-indigo-500 min-w-[200px] flex-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Total Bills</span>
                <h3 class="text-2xl font-black text-indigo-600 tracking-tight">{{ number_format($totalBillsCount) }}</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm group hover:shadow-md transition-all border-l-4 border-l-emerald-500 min-w-[220px] flex-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Total Revenue</span>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">
                    <span class="text-xs font-bold text-slate-400 mr-1">PKR</span>{{ number_format($totalRevenue, 0) }}
                </h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm group hover:shadow-md transition-all border-l-4 border-l-blue-500 min-w-[220px] flex-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Total Received</span>
                <h3 class="text-2xl font-black text-blue-600 tracking-tight">
                    <span class="text-xs font-bold text-slate-400 mr-1">PKR</span>{{ number_format($totalReceived, 0) }}
                </h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm group hover:shadow-md transition-all border-l-4 border-l-rose-500 min-w-[220px] flex-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Total Pending</span>
                <h3 class="text-2xl font-black text-rose-600 tracking-tight">
                    <span class="text-xs font-bold text-slate-400 mr-1">PKR</span>{{ number_format($totalPendingAmount, 0) }}
                </h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm group hover:shadow-md transition-all min-w-[200px] flex-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Month Revenue</span>
                <h3 class="text-xl font-black text-slate-600 tracking-tight">
                    <span class="text-xs font-bold text-slate-400 mr-1">PKR</span>{{ number_format($thisMonthRevenue, 0) }}
                </h3>
            </div>
        </div>

        <!-- Filter Bar -->
        <x-report-filter 
            :action="route('reports.billing')" 
            :showStatus="true" 
            :showClient="true" 
            :clients="$clients" 
        />

        <!-- Report Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Reference No</th>
                            <th class="px-6 py-4">Client Name</th>
                            <th class="px-6 py-4 text-center">Date</th>
                            <th class="px-6 py-4 text-right">Bill Amount</th>
                            <th class="px-6 py-4 text-right">Paid</th>
                            <th class="px-6 py-4 text-right">Balance</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bills as $bill)
                            <tr>
                                <td class="px-6 py-4 font-bold text-indigo-600">
                                    <a href="{{ route('bills.show', $bill) }}" class="hover:underline">{{ $bill->bill_no }}</a>
                                </td>
                                <td class="px-6 py-4 text-slate-700 font-bold">{{ $bill->client->name }}</td>
                                <td class="px-6 py-4 text-center text-slate-500">{{ \Carbon\Carbon::parse($bill->date)->format('d M, Y') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">{{ number_format($bill->total_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right text-emerald-600">{{ number_format($bill->paid_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right text-rose-500">{{ number_format($bill->balance, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if(strtolower($bill->status) === 'paid')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded uppercase">Paid</span>
                                    @else
                                        <span class="px-2 py-1 bg-rose-100 text-rose-700 text-[10px] font-bold rounded uppercase">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bills->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $bills->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-header hidden p-8 text-center border-b-2 border-slate-900 mb-8">
        <h1 class="text-2xl font-bold text-slate-900 uppercase">SEA PEARL SERVICES</h1>
        <p class="text-xs text-slate-500 uppercase tracking-widest">Customs Clearing Agent & Logistics</p>
        <h2 class="text-lg font-bold uppercase text-slate-800 mt-4">Billing Report</h2>
        <p class="text-xs mt-2">Printed: {{ date('d M, Y h:i A') }}</p>
    </div>

    <style>
        @media print {
            aside, nav, header, form, .print\:hidden { display: none !important; }
            body { background: white !important; }
            .print-header { display: block !important; }
            .bg-white { border: none !important; }
            table { border: 1px solid #ddd !important; }
            th, td { border: 1px solid #ddd !important; padding: 10px !important; }
        }
    </style>
</x-admin-layout>
