<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Operations Dashboard</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Live overview of clearances, bills and client activity — Karachi Port</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="btn-ghost py-2 text-[13px]"><i class="bi bi-download me-1"></i> Export</button>
                <button class="btn-soft py-2 text-[13px]"><i class="bi bi-calendar3 me-1"></i> This Month</button>
                <a href="{{ route('bills.create') }}" class="btn-brand py-2 text-[13px]"><i class="bi bi-plus-lg me-1"></i> New Bill</a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid-stats">
            <div class="card-c p-5 flex justify-between items-start">
                <div>
                    <div class="text-[#64748b] text-[11px] uppercase font-bold tracking-widest">Active Clients</div>
                    <div class="text-2xl font-bold mt-1 text-[#0f172a]">{{ number_format($totalClients) }}</div>
                    <div class="text-[#16a34a] text-[11px] font-semibold mt-1"><i class="bi bi-arrow-up-short"></i> 12.4% vs last month</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>

            <div class="card-c p-5 flex justify-between items-start">
                <div>
                    <div class="text-[#64748b] text-[11px] uppercase font-bold tracking-widest">Total Invoices</div>
                    <div class="text-2xl font-bold mt-1 text-[#0f172a]">{{ number_format($totalBills) }}</div>
                    <div class="text-[#16a34a] text-[11px] font-semibold mt-1"><i class="bi bi-arrow-up-short"></i> 6.1% growth</div>
                </div>
                <div class="stat-icon green"><i class="bi bi-receipt"></i></div>
            </div>

            <div class="card-c p-5 flex justify-between items-start">
                <div>
                    <div class="text-[#64748b] text-[11px] uppercase font-bold tracking-widest">Outstanding</div>
                    <div class="text-2xl font-bold mt-1 text-[#0f172a]">PKR {{ number_format($totalPendingAmount / 1000000, 1) }}M</div>
                    <div class="text-[#dc2626] text-[11px] font-semibold mt-1"><i class="bi bi-arrow-down-short"></i> 3.2% decrease</div>
                </div>
                <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            </div>

            <div class="card-c p-5 flex justify-between items-start">
                <div>
                    <div class="text-[#64748b] text-[11px] uppercase font-bold tracking-widest">Total Recovery</div>
                    <div class="text-2xl font-bold mt-1 text-[#0f172a]">PKR {{ number_format($totalRecovery / 1000000, 1) }}M</div>
                    <div class="text-[#16a34a] text-[11px] font-semibold mt-1"><i class="bi bi-arrow-up-short"></i> 9.7% vs last qtr</div>
                </div>
                <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            </div>
        </div>

        <div class="grid-2-cols mt-6">
            <!-- Today's Bills Table -->
            <div class="card-c overflow-hidden">
                <div class="card-head">
                    <h5>Today's Bills</h5>
                    <a href="{{ route('bills.index') }}" class="text-[12px] font-semibold text-[#1565c0] hover:underline">View all bills <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-c">
                        <thead>
                            <tr>
                                <th>Bill #</th>
                                <th>Client</th>
                                <th class="text-right">Amount (PKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayBills as $bill)
                            <tr>
                                <td class="font-bold text-[#1565c0]">{{ $bill->bill_no }}</td>
                                <td>{{ optional($bill->client)->name }}</td>
                                <td class="text-right font-bold">{{ number_format($bill->total_amount) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-[#64748b] italic">No bills created today.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Clients Table -->
            <div class="card-c overflow-hidden">
                <div class="card-head">
                    <h5>New Partnerships</h5>
                    <a href="{{ route('clients.index') }}" class="text-[12px] font-semibold text-[#1565c0] hover:underline">Manage clients <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-c">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>NTN / Email</th>
                                <th class="text-right">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestClients as $client)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-[#f1f5f9] text-[#334155] grid place-items-center font-bold text-[11px]">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold">{{ $client->name }}</span>
                                    </div>
                                </td>
                                <td class="text-[#64748b] text-[13px]">{{ $client->ntn_no ?: $client->email ?: '---' }}</td>
                                <td class="text-right text-[12px] text-[#64748b] font-medium">{{ $client->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-[#64748b] italic">No new clients recently.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
