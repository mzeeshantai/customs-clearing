<x-admin-layout>
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Outstanding Balances</h1>
                <p class="text-sm text-slate-500">Summary of unpaid bills across all client accounts</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-50 transition-all uppercase tracking-wider shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Report
                </button>
            </div>
        </div>
        
        <!-- Summary Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Total Outstanding</span>
                <h3 class="text-2xl font-bold text-rose-600">PKR {{ number_format($totalOutstanding, 0) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Active Debtors</span>
                <h3 class="text-2xl font-bold text-amber-600">{{ number_format($totalClientsWithBalance) }} Clients</h3>
            </div>
        </div>

        <!-- Filter Bar -->
        <x-report-filter 
            :action="route('reports.outstanding')" 
            :showDateRange="false" 
        />

        <!-- Report Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Client Name</th>
                            <th class="px-6 py-4 text-right">Total Billing</th>
                            <th class="px-6 py-4 text-right">Recovered</th>
                            <th class="px-6 py-4 text-right">Outstanding</th>
                            <th class="px-6 py-4 text-center">Last Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($paginatedClients as $client)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $client->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-700">{{ number_format($client->total_billing, 2) }}</td>
                                <td class="px-6 py-4 text-right text-emerald-600">{{ number_format($client->total_paid, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-rose-600">
                                    {{ number_format($client->remaining_balance, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center text-slate-500">
                                    {{ $client->last_payment_date ? \Carbon\Carbon::parse($client->last_payment_date)->format('d M, Y') : '---' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">No outstanding balances found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($paginatedClients->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $paginatedClients->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-header hidden p-8 text-center border-b-2 border-slate-900 mb-8">
        <h1 class="text-2xl font-bold text-slate-900 uppercase">SEA PEARL SERVICES</h1>
        <p class="text-xs text-slate-500 uppercase tracking-widest">Customs Clearing Agent & Logistics</p>
        <h2 class="text-lg font-bold uppercase text-slate-800 mt-4">Outstanding Balances Report</h2>
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
