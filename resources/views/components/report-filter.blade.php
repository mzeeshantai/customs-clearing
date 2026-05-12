@props([
    'action',
    'showDateRange' => true,
    'showStatus' => false,
    'showClient' => false,
    'clients' => [],
    'statuses' => [
        'paid' => 'Paid',
        'unpaid' => 'Unpaid'
    ]
])

<div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 print:hidden mb-8 overflow-hidden">
    <form action="{{ $action }}" method="GET" class="flex flex-row flex-wrap md:flex-nowrap items-end gap-3 p-2">
        @if($showDateRange)
            <div class="min-w-[160px] flex-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="block w-full py-2 px-3 border-none bg-slate-50 rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>
            <div class="min-w-[160px] flex-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="block w-full py-2 px-3 border-none bg-slate-50 rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>
        @endif

        @if($showStatus)
            <div class="min-w-[140px] flex-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Status</label>
                <select name="status" class="block w-full py-2 px-3 border-none bg-slate-50 rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($showClient)
            <div class="min-w-[160px] flex-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Client</label>
                <select name="client_id" class="block w-full py-2 px-3 border-none bg-slate-50 rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        {{ $slot }}

        <div class="flex items-center gap-1 pb-0.5">
            <button type="submit" class="flex items-center px-5 py-2.5 bg-indigo-600 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Generate
            </button>
            
            <button type="button" onclick="window.print()" class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all" title="Print Report">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            </button>

            @if(request()->anyFilled(['start_date', 'end_date', 'status', 'client_id', 'search']))
                <a href="{{ $action }}" class="p-2.5 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-100 transition-all" title="Clear Filters">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
    </form>
</div>
