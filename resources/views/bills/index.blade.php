<x-admin-layout>
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manage Bills</h1>
                <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <span>/</span>
                    <span class="text-slate-600">Bills</span>
                </div>
            </div>
        </div>

        <!-- Filter Section (Clean & Robust) -->
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 print:hidden mb-6">
            <form action="{{ route('bills.index') }}" method="GET" class="flex flex-nowrap items-center gap-3">
                <!-- Create Button -->
                <a href="{{ route('bills.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.15em] rounded-xl hover:bg-indigo-700 transition-all active:scale-95 shrink-0 shadow-lg shadow-indigo-100">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Create Bill
                </a>

                <!-- Search Box -->
                <div class="relative flex-1 min-w-[250px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Bill No, GD, Client..." 
                        class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-400">
                </div>

                <!-- Client Selection (Searchable) -->
                <div class="flex-1 min-w-[250px]" x-data="{ 
                    open: false, 
                    search: '', 
                    selectedId: '{{ request('client_id') }}',
                    selectedName: '{{ request('client_id') ? $clients->firstWhere('id', request('client_id'))->name : 'All Clients' }}',
                    clients: [
                        @foreach($clients as $client)
                            { id: '{{ $client->id }}', name: '{{ addslashes($client->name) }}' },
                        @endforeach
                    ],
                    get filteredClients() {
                        if (this.search === '') return this.clients;
                        return this.clients.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                    },
                    select(client) {
                        this.selectedId = client ? client.id : '';
                        this.selectedName = client ? client.name : 'All Clients';
                        this.open = false;
                        this.search = '';
                    }
                }" @click.away="open = false" class="relative">
                    <input type="hidden" name="client_id" :value="selectedId">
                    <button type="button" @click="open = !open" 
                        class="flex items-center justify-between w-full py-2.5 pl-4 pr-3 bg-white border border-slate-200 rounded-xl text-[13px] font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-left">
                        <span x-text="selectedName" class="truncate mr-2"></span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute z-50 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-2xl overflow-hidden min-w-[280px]">
                        <div class="p-2 border-b border-slate-50 bg-slate-50/50">
                            <input type="text" x-model="search" placeholder="Filter clients..." 
                                class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] font-medium focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        </div>
                        <div class="max-h-[240px] overflow-y-auto custom-scrollbar">
                            <button type="button" @click="select(null)" 
                                class="w-full px-4 py-2 text-left text-[11px] font-black text-rose-500 hover:bg-rose-50 transition-colors border-b border-slate-50 uppercase tracking-widest">
                                Clear Selection
                            </button>
                            <template x-for="client in filteredClients" :key="client.id">
                                <button type="button" @click="select(client)" 
                                    class="w-full px-4 py-2.5 text-left text-[12px] font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all border-b border-slate-50 last:border-0"
                                    :class="selectedId == client.id ? 'bg-indigo-50 text-indigo-600' : ''">
                                    <span x-text="client.name"></span>
                                </button>
                            </template>
                            <div x-show="filteredClients.length === 0" class="px-4 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                No clients found
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="w-[160px] shrink-0">
                    <select name="status" class="block w-full py-2.5 pl-4 pr-10 bg-white border border-slate-200 rounded-xl text-[13px] font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <!-- Button -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="submit" class="flex items-center px-6 py-2.5 bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.1em] rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'client_id', 'status']))
                        <a href="{{ route('bills.index') }}" class="p-2.5 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-100 transition-all" title="Clear Filters">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>


        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                            <th class="px-6 py-4">Bill No</th>
                            <th class="px-6 py-4">Date & Time</th>
                            <th class="px-6 py-4">Client Detail</th>
                            <th class="px-6 py-4 text-center">GD Number</th>
                            <th class="px-6 py-4 text-center">Location</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-center print:hidden">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($bills as $bill)
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-indigo-600 tracking-tight">{{ $bill->bill_no }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-700 font-semibold text-[13px]">{{ date('M d, Y', strtotime($bill->date)) }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium">{{ date('h:i A', strtotime($bill->date)) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-[13px] leading-tight">{{ $bill->client->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-semibold tracking-tight mt-0.5">{{ $bill->client->ntn_no }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-slate-600 font-semibold tracking-tight">{{ $bill->gd_no }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-wider border border-slate-200/50">{{ $bill->location }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div x-data="{
                                        status: '{{ $bill->status }}',
                                        method: '{{ $bill->payment_method ?? '' }}',
                                        originalStatus: '{{ $bill->status }}',
                                        originalMethod: '{{ $bill->payment_method ?? '' }}',
                                        get changed() { return this.status !== this.originalStatus || (this.status === 'paid' && this.method !== this.originalMethod); }
                                    }" class="flex flex-col items-center">
                                        
                                        <div class="flex items-center gap-2">
                                            <select x-model="status" 
                                                class="text-[11px] font-bold uppercase tracking-wider border-0 rounded-full cursor-pointer focus:ring-0 px-4 py-1.5 transition-all shadow-sm"
                                                :class="status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200'">
                                                <option value="unpaid">● Unpaid</option>
                                                <option value="paid">● Paid</option>
                                            </select>

                                            <template x-if="status === 'paid'">
                                                <select x-model="method"
                                                    class="text-[11px] border border-slate-200 bg-white rounded-xl focus:ring-4 focus:ring-indigo-500/10 text-slate-700 font-bold px-3 py-1.5 h-8">
                                                    <option value="">Select Method</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="bank_transfer">Bank</option>
                                                    <option value="pay_order">P.O</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="online">Online</option>
                                                </select>
                                            </template>
                                        </div>

                                        <template x-if="changed">
                                            <form action="{{ route('bills.update-status', $bill) }}" method="POST" class="mt-2.5 w-full max-w-[140px]">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" :value="status">
                                                <input type="hidden" name="payment_method" :value="method">
                                                <button type="submit" 
                                                    :disabled="status === 'paid' && !method"
                                                    class="w-full text-[10px] font-bold uppercase tracking-[0.1em] rounded-xl px-3 py-2 transition-all shadow-lg disabled:opacity-30 active:scale-95"
                                                    :class="status === 'paid' ? 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-200' : 'bg-rose-600 text-white hover:bg-rose-700 shadow-rose-200'">
                                                    Apply Changes
                                                </button>
                                            </form>
                                        </template>

                                        <template x-if="!changed && status === 'paid' && method">
                                            <div class="mt-2 flex items-center px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-lg text-[10px] font-bold uppercase tracking-tight">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                <span x-text="method.replace(/_/g, ' ')"></span>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-[14px] font-bold text-slate-900 tracking-tight">{{ number_format($bill->total_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center print:hidden">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('bills.show', $bill) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-xl transition-all" title="View Bill">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('bills.edit', $bill) }}" class="p-2 text-slate-400 hover:bg-slate-100 rounded-xl transition-all" title="Edit Bill">
                                                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('bills.destroy', $bill) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-400 hover:bg-rose-50 rounded-xl transition-all" title="Delete Bill" onclick="return confirm('Are you sure you want to delete this bill?')">
                                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-20 text-center text-slate-400 font-bold italic bg-slate-50/20">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-slate-100 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        No bills found matching your criteria.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bills->hasPages())
                <div class="px-6 py-5 bg-slate-50/30 border-t border-slate-100 print:hidden">
                    {{ $bills->links() }}
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
            th, td { border: 1px solid #e2e8f0 !important; color: #000 !important; padding: 12px !important; }
            .border-none { border: none !important; }
        }
    </style>
</x-admin-layout>
