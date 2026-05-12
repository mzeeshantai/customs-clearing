<x-admin-layout>
    <div class="flex flex-col space-y-8" x-data="{ 
        showModal: false,
        selectedClient: {{ $topClients->first() ? json_encode([
            'name' => $topClients->first()->name,
            'total_revenue' => $topClients->first()->total_revenue,
            'total_paid' => $topClients->first()->total_paid,
            'pending_amount' => $topClients->first()->pending_amount,
            'monthly_collection' => $topClients->first()->monthly_collection
        ]) : 'null' }}
    }">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Client Performance</h1>
                <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <span>/</span>
                    <span class="text-slate-600">Client Summary</span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-black rounded-xl hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-widest shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Summary
                </button>
            </div>
        </div>
        
        <!-- Top Clients Section -->
        @if($topClients->count() > 0)
        <div>
            <h2 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4 ml-1">Top Performing Partners</h2>
            <div class="flex flex-nowrap items-stretch gap-4 overflow-x-auto pb-6 custom-scrollbar lg:overflow-visible lg:pb-0">
                @foreach($topClients as $index => $topClient)
                    <div class="relative overflow-hidden bg-indigo-600 p-4 rounded-[1.5rem] shadow-xl group transition-all hover:scale-105 min-w-[240px] flex-1 cursor-pointer"
                         @click="selectedClient = { 
                            name: '{{ $topClient->name }}', 
                            total_revenue: {{ $topClient->total_revenue ?: 0 }},
                            total_paid: {{ $topClient->total_paid ?: 0 }},
                            pending_amount: {{ $topClient->pending_amount ?: 0 }},
                            monthly_collection: {{ $topClient->monthly_collection ?: 0 }}
                         }; showModal = true">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <span class="px-2 py-0.5 bg-white/20 text-white text-[9px] font-black uppercase rounded tracking-widest border border-white/10">Rank #{{ $index + 1 }}</span>
                                <h4 class="text-sm font-black text-white mt-2 truncate leading-tight uppercase tracking-tight">{{ $topClient->name }}</h4>
                            </div>
                            <div class="mt-8 flex items-end justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest">Revenue</span>
                                    <span class="text-lg font-black text-white tracking-tighter">{{ number_format($topClient->total_revenue, 0) }} <span class="text-[9px]">PKR</span></span>
                                </div>
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Modal for Client Insights -->
    <div x-show="showModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Modal Content -->
        <div class="relative bg-slate-50 w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             @click.away="showModal = false">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Partner Profile</p>
                        <h2 class="text-lg font-black text-slate-800 tracking-tight" x-text="selectedClient ? selectedClient.name : ''"></h2>
                    </div>
                </div>
                <button @click="showModal = false" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="selectedClient">
                    <!-- Lifetime Revenue -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 border-l-4 border-l-emerald-500 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Lifetime Revenue</span>
                        <h3 class="text-xl font-black text-emerald-600 tracking-tight" x-text="selectedClient ? new Intl.NumberFormat().format(selectedClient.total_revenue) + ' PKR' : '0 PKR'"></h3>
                    </div>

                    <!-- Total Received -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 border-l-4 border-l-blue-500 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Total Received</span>
                        <h3 class="text-xl font-black text-blue-600 tracking-tight" x-text="selectedClient ? new Intl.NumberFormat().format(selectedClient.total_paid) + ' PKR' : '0 PKR'"></h3>
                    </div>

                    <!-- Total Pending -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 border-l-4 border-l-rose-500 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Total Pending</span>
                        <h3 class="text-xl font-black text-rose-600 tracking-tight" x-text="selectedClient ? new Intl.NumberFormat().format(selectedClient.pending_amount) + ' PKR' : '0 PKR'"></h3>
                    </div>

                    <!-- Monthly Collection -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 border-l-4 border-l-amber-500 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Monthly Collection</span>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="selectedClient ? new Intl.NumberFormat().format(selectedClient.monthly_collection) + ' PKR' : '0 PKR'"></h3>
                    </div>
                </div>

                <div class="mt-6 flex justify-center">
                    <button @click="showModal = false" class="w-full py-3 bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-900 transition-colors shadow-lg active:scale-95 transition-all">
                        Back to Report
                    </button>
                </div>
            </div>
        </div>
    </div>

        <!-- Filter Bar -->
        <x-report-filter 
            :action="route('reports.clients')" 
            :showDateRange="false"
        >
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Search Client / NTN</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                    class="block w-full py-2 px-3 border-none bg-slate-50 rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Sort Hierarchy</label>
                <select name="sort" class="block w-full py-2 px-3 border-none bg-slate-50 rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="revenue_desc" {{ request('sort') === 'revenue_desc' ? 'selected' : '' }}>Highest Revenue</option>
                    <option value="revenue_asc" {{ request('sort') === 'revenue_asc' ? 'selected' : '' }}>Lowest Revenue</option>
                </select>
            </div>
        </x-report-filter>

        <!-- Report Data Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 font-bold uppercase tracking-[0.15em] text-[10px]">
                            <th class="px-6 py-5 border-b border-slate-100">Client Name</th>
                            <th class="px-6 py-5 border-b border-slate-100 text-center">Invoices</th>
                            <th class="px-6 py-5 border-b border-slate-100 text-right">Revenue Generated</th>
                            <th class="px-6 py-5 border-b border-slate-100 text-right">Payment Received</th>
                            <th class="px-6 py-5 border-b border-slate-100 text-right">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($paginatedClients as $client)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-5 font-black text-slate-800 uppercase tracking-tight">{{ $client->name }}</td>
                                <td class="px-6 py-5 text-center text-slate-600 font-black">{{ $client->total_bills }}</td>
                                <td class="px-6 py-5 text-right font-black text-slate-900">{{ number_format($client->total_revenue, 2) }}</td>
                                <td class="px-6 py-5 text-right font-black text-blue-600">{{ number_format($client->total_paid, 2) }}</td>
                                <td class="px-6 py-5 text-right font-black text-rose-500">{{ number_format($client->pending_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-slate-400 font-black italic">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($paginatedClients->hasPages())
                <div class="px-6 py-5 bg-slate-50/30 border-t border-slate-100 print:hidden">
                    {{ $paginatedClients->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-header hidden p-8 text-center border-b-2 border-slate-900 mb-8 bg-slate-50">
        <h1 class="text-3xl font-black tracking-tighter text-slate-900 mb-1">SEA PEARL SERVICES</h1>
        <p class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Customs Clearing Agent & Logistics</p>
        <h2 class="text-xl font-bold uppercase text-slate-800 border-t border-slate-200 pt-4 mt-4 tracking-widest">Client Summary Report</h2>
        <div class="flex items-center justify-center space-x-6 mt-4 text-xs font-bold text-slate-600">
            <span>Printed: {{ date('d M, Y h:i A') }}</span>
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
            .print-header { display: block !important; }
            .rounded-3xl { border-radius: 0 !important; }
        }
    </style>
</x-admin-layout>
