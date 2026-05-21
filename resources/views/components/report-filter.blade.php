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

<div class="card-c p-4 print:hidden mb-6">
    <form action="{{ $action }}" method="GET" class="filter-row">
        @if($showDateRange)
            <div style="flex: 1; min-width: 140px;">
                <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
            </div>
            <div style="flex: 1; min-width: 140px;">
                <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
            </div>
        @endif

        @if($showStatus)
            <div style="flex: 1; min-width: 140px;">
                <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Status</label>
                <select name="status" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($showClient)
            <div style="flex: 2; min-width: 200px;">
                <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Client</label>
                <select name="client_id" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="flex gap-2" style="flex-shrink: 0;">
            <button type="submit" class="btn-brand px-6 h-[38px] text-[13px] shadow-md">
                <i class="bi bi-funnel me-1"></i> Generate
            </button>
            <button type="button" onclick="window.print()" class="btn-soft px-3 h-[38px] text-[13px] shadow-sm" title="Print">
                <i class="bi bi-printer"></i>
            </button>
            @if(request()->anyFilled(['start_date', 'end_date', 'status', 'client_id']))
                <a href="{{ $action }}" class="btn-ghost px-3 h-[38px] text-[#dc2626] border-none bg-[#fee2e2] hover:bg-[#fecaca] rounded-xl transition-all flex items-center" title="Reset">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>
