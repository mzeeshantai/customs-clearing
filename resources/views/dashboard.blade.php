<x-admin-layout>
    <div class="flex flex-col space-y-6">
        <!-- Header (Modern SaaS Polish) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Overview</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1">Hello, {{ Auth::user()->name }}. Here is what's happening today.</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <span class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse mr-2.5"></span>
                    <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">System Live</span>
                </div>
            </div>
        </div>

        <!-- Summary Cards (Modern Polish) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Clients -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Total Clients</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalClients) }}</h3>
            </div>

            <!-- Total Bills -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Total Invoices</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalBills) }}</h3>
            </div>

            <!-- Total Pending -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 bg-rose-50 rounded-xl text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Outstanding</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-1">PKR {{ number_format($totalPendingAmount) }}</h3>
            </div>

            <!-- Total Recovery -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Total Recovery</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-1">PKR {{ number_format($totalRecovery) }}</h3>
            </div>
        </div>

        <!-- Recent Activity (Modern SaaS Polish) -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Latest Transactions -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden group">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider opacity-80">Latest Transactions</h3>
                    <a href="{{ route('bills.index') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 transition-colors uppercase tracking-widest bg-indigo-50 px-3 py-1.5 rounded-lg">Browse All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                                <th class="px-6 py-4">Reference</th>
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestTransactions as $bill)
                            <tr class="hover:bg-slate-50/50 transition-colors group/row">
                                <td class="px-6 py-4">
                                    <span class="text-[13px] font-bold text-indigo-600 tracking-tight">{{ $bill->bill_no }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-700">{{ optional($bill->client)->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-[13px] font-bold text-slate-900 tracking-tight">PKR {{ number_format($bill->total_amount) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic text-sm">No recent transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Clients -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden group">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider opacity-80">Recent Clients</h3>
                    <a href="{{ route('clients.index') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-lg">Browse All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                                <th class="px-6 py-4">Account Name</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4 text-right">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestClients as $client)
                            <tr class="hover:bg-slate-50/50 transition-colors group/row">
                                <td class="px-6 py-4">
                                    <span class="text-[13px] font-bold text-slate-700 tracking-tight">{{ $client->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[13px] font-medium text-slate-500">{{ $client->email ?: '---' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-[12px] font-semibold text-slate-400 uppercase tracking-tight">{{ $client->created_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic text-sm">No new clients joined recently.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
