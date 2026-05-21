<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Expense Management</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Track and manage operational expenses</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('expenses.create') }}" class="btn-brand py-2 text-[13px]">
                    <i class="bi bi-plus-lg me-1"></i> Add Expense
                </a>
            </div>
        </div>

        <x-flash-messages />

        <!-- Filters -->
        <div class="card-c p-4 mb-6">
            <form action="{{ route('expenses.index') }}" method="GET" class="filter-row flex flex-wrap gap-4 items-end">
                
                <div style="flex: 1; min-width: 140px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Payment Method</label>
                    <select name="payment_method" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                        <option value="">All Methods</option>
                        <option value="Cash" {{ request('payment_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank Transfer" {{ request('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cheque" {{ request('payment_method') === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Online Transfer" {{ request('payment_method') === 'Online Transfer' ? 'selected' : '' }}>Online Transfer</option>
                    </select>
                </div>
                
                <div style="flex: 1; min-width: 140px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none cursor-pointer shadow-sm">
                        <option value="">All Statuses</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                
                <div style="flex: 2; min-width: 200px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, Reference No..." class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
                </div>

                <div class="flex gap-2" style="flex-shrink: 0;">
                    <button type="submit" class="btn-brand px-6 h-[38px] text-[13px] shadow-md">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->anyFilled(['start_date', 'end_date', 'category_id', 'payment_method', 'status', 'search']))
                        <a href="{{ route('expenses.index') }}" class="btn-ghost px-3 h-[38px] text-[#dc2626] border-none bg-[#fee2e2] hover:bg-[#fecaca] rounded-xl transition-all flex items-center" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th class="w-16 text-center">ID</th>
                            <th>Date</th>
                            <th>Title / Reference</th>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                            <th class="text-center">Payment</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="text-center font-bold text-[#64748b]">EXP-{{ str_pad($expense->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="text-[#334155] font-medium">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}</td>
                                <td>
                                    <div class="font-bold text-[#0f172a]">{{ $expense->title }}</div>
                                    @if($expense->reference_no)
                                        <div class="text-[11px] text-[#64748b] mt-0.5"><i class="bi bi-hash"></i> {{ $expense->reference_no }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-c bg-[#f1f5f9] text-[#475569] border-[#e2e8f0]">
                                        {{ $expense->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="text-right font-bold text-[#0f172a]">Rs. {{ number_format($expense->amount, 2) }}</td>
                                <td class="text-center text-[12px] text-[#64748b] font-medium">{{ $expense->payment_method }}</td>
                                <td class="text-center">
                                    @if($expense->status === 'paid')
                                        <span class="badge-c success py-1 px-3 text-[10px]">Paid</span>
                                    @else
                                        <span class="badge-c warning py-1 px-3 text-[10px]">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('expenses.show', $expense) }}" class="w-8 h-8 rounded-lg grid place-items-center text-[#1565c0] hover:bg-[#eff6ff] transition-all" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="w-8 h-8 rounded-lg grid place-items-center text-[#64748b] hover:bg-[#f8fafc] transition-all" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg grid place-items-center text-[#dc2626] hover:bg-[#fee2e2] transition-all" title="Delete" onclick="return confirm('Are you sure you want to delete this expense?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-16 text-[#64748b] bg-[#f8fafc]/50">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-[#f1f5f9] rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-wallet2 text-3xl text-[#cbd5e1]"></i>
                                        </div>
                                        <div class="font-bold">No expenses found</div>
                                        <div class="text-sm mt-1">Adjust filters or record a new expense</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e5e9f2]">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>
