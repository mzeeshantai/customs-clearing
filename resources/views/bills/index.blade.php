<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Bill Management</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Search and manage all customs clearance bills of entry</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="btn-ghost py-2 text-[13px]"><i class="bi bi-download me-1"></i> Export Data</button>
                <a href="{{ route('bills.create') }}" class="btn-brand py-2 text-[13px]"><i class="bi bi-plus-lg me-1"></i> Create New Bill</a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card-c p-4 overflow-x-auto">
            <form action="{{ route('bills.index') }}" method="GET" class="filter-row min-w-[800px]">
                <!-- Search Reference - Larger -->
                <div style="flex: 3;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Search Reference</label>
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Bill No, GD, or Cargo..." 
                            class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm focus:border-[#1565c0] focus:ring-0 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- Filter by Client - Standard Select -->
                <div style="flex: 2;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Filter by Client</label>
                    <select name="client_id" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status - Smaller -->
                <div style="width: 150px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-brand px-5 py-2 text-[13px] whitespace-nowrap shadow-md h-[38px]">
                        <i class="bi bi-funnel me-1"></i> Apply Filter
                    </button>
                    @if(request()->anyFilled(['search', 'client_id', 'status']))
                        <a href="{{ route('bills.index') }}" class="btn-ghost px-3 py-2 text-[#dc2626] border-none bg-[#fee2e2] hover:bg-[#fecaca] rounded-xl transition-all h-[38px] flex items-center" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Client Details</th>
                            <th class="text-center">GD Number</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Total Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bills as $bill)
                            <tr>
                                <td><span class="font-bold text-[#1565c0]">{{ $bill->bill_no }}</span></td>
                                <td>
                                    <div class="font-semibold text-[#0f172a]">{{ date('M d, Y', strtotime($bill->date)) }}</div>
                                    <div class="text-[11px] text-[#64748b]">{{ date('h:i A', strtotime($bill->date)) }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-[#0f172a]">{{ $bill->client->name }}</div>
                                    <div class="text-[11px] text-[#64748b] font-medium tracking-tight mt-0.5">{{ $bill->client->ntn_no }}</div>
                                </td>
                                <td class="text-center font-semibold text-[#64748b]">{{ $bill->gd_no }}</td>
                                <td class="text-center">
                                    <div x-data="{
                                        status: '{{ $bill->status }}',
                                        method: '{{ $bill->payment_method ?? '' }}',
                                        originalStatus: '{{ $bill->status }}',
                                        get changed() { return this.status !== this.originalStatus; }
                                    }" class="flex flex-col items-center gap-1">
                                        <select x-model="status" 
                                            class="badge-c border-none outline-none cursor-pointer py-1.5 px-3"
                                            :class="status === 'paid' ? 'success' : 'danger'">
                                            <option value="unpaid">● Unpaid</option>
                                            <option value="paid">● Paid</option>
                                        </select>
                                        
                                        <template x-if="changed">
                                            <form action="{{ route('bills.update-status', $bill) }}" method="POST" class="mt-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" :value="status">
                                                <input type="hidden" name="payment_method" value="cash"> {{-- Simplified for brevity, usually should ask for method --}}
                                                <button type="submit" class="text-[10px] font-bold text-white bg-[#0b3d91] px-2 py-1 rounded-md uppercase tracking-widest hover:bg-[#082e6e]">Apply</button>
                                            </form>
                                        </template>

                                        @if($bill->status == 'paid' && $bill->payment_method)
                                            <span class="text-[9px] font-bold text-[#64748b] uppercase tracking-widest mt-0.5">{{ str_replace('_', ' ', $bill->payment_method) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-right font-bold text-[#0f172a]">{{ number_format($bill->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('bills.show', $bill) }}" class="w-8 h-8 rounded-lg grid place-items-center text-[#1565c0] hover:bg-[#eaf2ff] transition-all" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('bills.edit', $bill) }}" class="w-8 h-8 rounded-lg grid place-items-center text-[#64748b] hover:bg-[#f8fafc] transition-all" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('bills.destroy', $bill) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg grid place-items-center text-[#dc2626] hover:bg-[#fee2e2] transition-all" title="Delete" onclick="return confirm('Are you sure you want to delete this bill?')">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-20 text-[#64748b] bg-[#f8fafc]/50">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-[#f1f5f9] rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-receipt text-3xl text-[#cbd5e1]"></i>
                                        </div>
                                        <div class="font-bold">No bills found matching your criteria</div>
                                        <div class="text-sm mt-1">Try adjusting your filters or search term</div>
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
</x-admin-layout>
