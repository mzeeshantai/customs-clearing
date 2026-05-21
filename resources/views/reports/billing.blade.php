<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Financial Billing Report</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Detailed audit trail of all generated invoices and payment statuses</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="btn-soft py-2 text-[13px]">
                    <i class="bi bi-printer me-1"></i> Print / PDF
                </button>
            </div>
        </div>
        
        <!-- Summary Stats (Reference Style) -->
        <div class="grid-5-cols">
            <div class="card-c p-4 border-l-4 border-l-[#1565c0]">
                <div class="text-[#64748b] text-[10px] uppercase font-bold tracking-widest">Total Bills</div>
                <div class="text-xl font-bold mt-1 text-[#0f172a]">{{ number_format($totalBillsCount) }}</div>
            </div>

            <div class="card-c p-4 border-l-4 border-l-[#16a34a]">
                <div class="text-[#64748b] text-[10px] uppercase font-bold tracking-widest">Gross Revenue</div>
                <div class="text-xl font-bold mt-1 text-[#0f172a]"><span class="text-[10px] text-[#64748b] me-1">PKR</span>{{ number_format($totalRevenue, 0) }}</div>
            </div>

            <div class="card-c p-4 border-l-4 border-l-[#0b3d91]">
                <div class="text-[#64748b] text-[10px] uppercase font-bold tracking-widest">Total Recovery</div>
                <div class="text-xl font-bold mt-1 text-[#0f172a]"><span class="text-[10px] text-[#64748b] me-1">PKR</span>{{ number_format($totalReceived, 0) }}</div>
            </div>

            <div class="card-c p-4 border-l-4 border-l-[#dc2626]">
                <div class="text-[#64748b] text-[10px] uppercase font-bold tracking-widest">Outstanding</div>
                <div class="text-xl font-bold mt-1 text-[#0f172a]"><span class="text-[10px] text-[#64748b] me-1">PKR</span>{{ number_format($totalPendingAmount, 0) }}</div>
            </div>

            <div class="card-c p-4">
                <div class="text-[#64748b] text-[10px] uppercase font-bold tracking-widest">This Month</div>
                <div class="text-xl font-bold mt-1 text-[#0f172a]"><span class="text-[10px] text-[#64748b] me-1">PKR</span>{{ number_format($thisMonthRevenue, 0) }}</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <x-report-filter 
            :action="route('reports.billing')" 
            :showStatus="true" 
            :showClient="true" 
            :clients="$clients" 
        />

        <!-- Report Table -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th>Reference No</th>
                            <th>Client Account</th>
                            <th class="text-center">Bill Date</th>
                            <th class="text-right">Bill Amount</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Balance</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bills as $bill)
                            <tr>
                                <td>
                                    <a href="{{ route('bills.show', $bill) }}" class="font-bold text-[#1565c0] hover:underline">
                                        {{ $bill->bill_no }}
                                    </a>
                                </td>
                                <td><div class="font-bold text-[#0f172a]">{{ $bill->client->name }}</div></td>
                                <td class="text-center text-[#64748b] font-medium">{{ \Carbon\Carbon::parse($bill->date)->format('d M, Y') }}</td>
                                <td class="text-right font-bold text-[#0f172a]">{{ number_format($bill->total_amount, 2) }}</td>
                                <td class="text-right text-[#16a34a] font-semibold">{{ number_format($bill->paid_amount, 2) }}</td>
                                <td class="text-right text-[#dc2626] font-bold">{{ number_format($bill->balance, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge-c {{ strtolower($bill->status) === 'paid' ? 'success' : 'danger' }} py-1 px-3 text-[10px]">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-20 text-[#64748b] bg-[#f8fafc]/50">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-search text-3xl mb-2"></i>
                                        <div class="font-bold">No records found for the selected period</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bills->hasPages())
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e5e9f2]">
                    {{ $bills->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Print Optimized View -->
    <div class="print-only hidden p-8 border-2 border-[#0b1f3a] mb-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#0b1f3a] uppercase">Sea Pearl Services</h1>
                <p class="text-xs text-[#64748b] font-bold uppercase tracking-widest">Customs Clearing Agent & Logistics</p>
            </div>
            <div class="text-right text-xs">
                <div class="font-bold">Generated:</div>
                <div>{{ date('d M, Y h:i A') }}</div>
            </div>
        </div>
        <div class="text-center mb-8 border-y border-[#e5e9f2] py-4 bg-[#f8fafc]">
            <h2 class="text-lg font-bold uppercase text-[#0b1f3a]">Financial Billing Statement</h2>
            <p class="text-xs text-[#64748b] mt-1 italic">Date Range: {{ request('start_date', 'All Time') }} to {{ request('end_date', 'Present') }}</p>
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
            .badge-c { background: transparent !important; border: 1px solid #ccc !important; padding: 2px 5px !important; }
        }
    </style>
</x-admin-layout>
