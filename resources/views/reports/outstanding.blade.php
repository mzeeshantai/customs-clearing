<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Outstanding Receivables</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Consolidated view of all unpaid client balances and aging debt</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="btn-soft py-2 text-[13px]">
                    <i class="bi bi-printer me-1"></i> Print Summary
                </button>
            </div>
        </div>
        
        <!-- Summary Widgets -->
        <div class="grid-2-cols gap-6">
            <div class="card-c p-6 border-l-4 border-l-[#dc2626]">
                <div class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest">Total Outstanding Exposure</div>
                <div class="text-3xl font-black text-[#0f172a] mt-2">
                    <span class="text-sm me-1 opacity-60">PKR</span>{{ number_format($totalOutstanding, 0) }}
                </div>
                <div class="text-[12px] text-[#64748b] mt-1 font-medium italic">Uncollected revenue across all accounts</div>
            </div>

            <div class="card-c p-6 border-l-4 border-l-[#1565c0]">
                <div class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest">Active Debtors</div>
                <div class="text-3xl font-black text-[#0f172a] mt-2">{{ number_format($totalClientsWithBalance) }} <span class="text-sm font-bold opacity-60 uppercase">Accounts</span></div>
                <div class="text-[12px] text-[#64748b] mt-1 font-medium italic">Clients with non-zero outstanding balances</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <x-report-filter 
            :action="route('reports.outstanding')" 
            :showDateRange="false" 
        />

        <!-- Report Data Table -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th>Client Account</th>
                            <th class="text-right">Total Invoiced</th>
                            <th class="text-right">Total Recovered</th>
                            <th class="text-right">Balance Due</th>
                            <th class="text-center">Last Transaction</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginatedClients as $client)
                            <tr class="hover:bg-[#f8fafc]">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#f1f5f9] grid place-items-center font-bold text-[#1565c0] text-xs">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        <div class="font-bold text-[#0f172a]">{{ $client->name }}</div>
                                    </div>
                                </td>
                                <td class="text-right font-semibold text-[#334155]">{{ number_format($client->total_billing, 2) }}</td>
                                <td class="text-right text-[#16a34a] font-semibold">{{ number_format($client->total_paid, 2) }}</td>
                                <td class="text-right font-black text-[#dc2626]">
                                    {{ number_format($client->remaining_balance, 2) }}
                                </td>
                                <td class="text-center text-[#64748b] font-medium italic text-[11px]">
                                    {{ $client->last_payment_date ? \Carbon\Carbon::parse($client->last_payment_date)->format('d M, Y') : 'No History' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-20 text-[#64748b] bg-[#f8fafc]/50">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-shield-check text-3xl mb-2 text-[#16a34a]"></i>
                                        <div class="font-bold">No outstanding balances found</div>
                                        <p class="text-xs mt-1">All client accounts are currently settled.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($paginatedClients->hasPages())
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e5e9f2]">
                    {{ $paginatedClients->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Print View -->
    <div class="print-only hidden p-8 border-2 border-[#0b1f3a] mb-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#0b1f3a] uppercase">Sea Pearl Services</h1>
                <p class="text-xs text-[#64748b] font-bold uppercase tracking-widest">Customs Clearing Agent & Logistics</p>
            </div>
            <div class="text-right text-xs">
                <div class="font-bold">Statement Date:</div>
                <div>{{ date('d M, Y h:i A') }}</div>
            </div>
        </div>
        <div class="text-center mb-8 border-y border-[#e5e9f2] py-4 bg-[#f8fafc]">
            <h2 class="text-lg font-bold uppercase text-[#0b1f3a]">Outstanding Balances Summary</h2>
        </div>
    </div>

    <style>
        @media print {
            aside, nav, header, form, .print-hidden, .card-c.p-4 { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            main { padding: 0 !important; max-width: 100% !important; }
            .print-only { display: block !important; }
            .card-c { border: none !important; box-shadow: none !important; }
            .table-c { border: 1px solid #e5e9f2 !important; width: 100% !important; }
            .table-c th, .table-c td { border: 1px solid #e5e9f2 !important; padding: 8px !important; color: black !important; }
        }
    </style>
</x-admin-layout>
