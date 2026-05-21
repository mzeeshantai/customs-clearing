<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Expense Reports</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Analytical overview of all operational expenses</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="btn-soft py-2 text-[13px]">
                    <i class="bi bi-printer me-1"></i> Print / PDF
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card-c p-5 border-l-4 border-l-[#0f172a]">
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Total Operational Cost</p>
                <div class="text-2xl font-bold text-[#0f172a]">Rs. {{ number_format($totalExpenses, 2) }}</div>
            </div>
            <div class="card-c p-5 border-l-4 border-l-[#1565c0]">
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Total Invoices/Slips</p>
                <div class="text-2xl font-bold text-[#0f172a]">{{ number_format($expenses->count()) }}</div>
            </div>
            <div class="card-c p-5 border-l-4 border-l-[#f59e0b]">
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Salary Expenses</p>
                <div class="text-2xl font-bold text-[#0f172a]">Rs. {{ number_format($salaryExpenses, 2) }}</div>
            </div>
            <div class="card-c p-5 border-l-4 border-l-[#10b981]">
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Utility Expenses</p>
                <div class="text-2xl font-bold text-[#0f172a]">Rs. {{ number_format($utilityExpenses, 2) }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-c p-4 mb-6 print:hidden">
            <form action="{{ route('reports.expenses') }}" method="GET" class="filter-row flex flex-wrap gap-4 items-end">
                
                <div style="flex: 1; min-width: 140px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Month</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
                </div>

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

                <div class="flex gap-2" style="flex-shrink: 0;">
                    <button type="submit" class="btn-brand px-6 h-[38px] text-[13px] shadow-md">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->anyFilled(['month', 'start_date', 'end_date', 'category_id', 'payment_method']))
                        <a href="{{ route('reports.expenses', ['clear_filters' => 1]) }}" class="btn-ghost px-3 h-[38px] text-[#dc2626] border-none bg-[#fee2e2] hover:bg-[#fecaca] rounded-xl transition-all flex items-center" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2">
                <!-- Breakdown Table -->
                <div class="card-c overflow-hidden h-full">
                    <div class="p-5 border-b border-[#e5e9f2] bg-[#f8fafc]">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest">Category Breakdown</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table-c">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-right">Total Amount</th>
                                    <th class="text-right">% of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryBreakdown as $category => $amount)
                                    <tr>
                                        <td class="font-bold text-[#0f172a]">{{ $category }}</td>
                                        <td class="text-right text-[#334155] font-semibold">Rs. {{ number_format($amount, 2) }}</td>
                                        <td class="text-right text-[#64748b]">
                                            {{ $totalExpenses > 0 ? number_format(($amount / $totalExpenses) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-8 text-[#64748b]">No data available for the selected period</td>
                                    </tr>
                                @endforelse
                                @if(count($categoryBreakdown) > 0)
                                    <tr class="bg-[#f8fafc]">
                                        <td class="font-bold text-[#0f172a] text-right">GRAND TOTAL</td>
                                        <td class="text-right font-bold text-[#1565c0] text-lg">Rs. {{ number_format($totalExpenses, 2) }}</td>
                                        <td class="text-right font-bold text-[#0f172a]">100%</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Payment Method Breakdown -->
                <div class="card-c overflow-hidden">
                    <div class="p-5 border-b border-[#e5e9f2] bg-[#f8fafc]">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest">By Payment Method</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($paymentMethodBreakdown as $method => $amount)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-semibold text-[#334155]">{{ $method }}</span>
                                    <span class="font-bold text-[#0f172a]">Rs. {{ number_format($amount, 2) }}</span>
                                </div>
                                <div class="w-full bg-[#f1f5f9] rounded-full h-2">
                                    <div class="bg-[#1565c0] h-2 rounded-full" style="width: {{ $totalExpenses > 0 ? ($amount / $totalExpenses) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-[#64748b] py-4 text-sm">No payment data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
    <style>
        @media print {
            aside, nav, header, form, .print\:hidden, .btn-soft { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            main { padding: 0 !important; max-width: 100% !important; }
            .card-c { border: 1px solid #e5e9f2 !important; box-shadow: none !important; }
        }
    </style>
</x-admin-layout>
