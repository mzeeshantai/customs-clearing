<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header & Print -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-sm text-[#64748b] hover:text-[#1565c0] transition-colors mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Reports
                </a>
                <h1 class="text-2xl font-bold text-[#0f172a]">Monthly Financial Report</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Overall business cash flow, profits, and expenses</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="btn-soft py-2 text-[13px]">
                    <i class="bi bi-printer me-1"></i> Print / PDF
                </button>
            </div>
        </div>

        <!-- Filters & Toggles -->
        <div class="card-c p-5 mb-6 print:hidden">
            <form action="{{ route('reports.financial') }}" method="GET" class="flex flex-wrap items-end gap-5">
                
                <div style="flex: 1; min-width: 140px; max-width: 200px;">
                    <label class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5 block ml-1">Month</label>
                    <input type="month" name="month" value="{{ $monthFilter }}" class="w-full px-4 py-2 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl text-sm outline-none focus:border-[#1565c0] transition-all shadow-sm">
                </div>

                <div class="flex items-center gap-4 border-l border-[#e5e9f2] pl-5 h-[38px]">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="deduct_tax" value="1" {{ $deductTax ? 'checked' : '' }} onchange="this.form.submit()" class="w-5 h-5 border-2 border-[#cbd5e1] rounded text-[#1565c0] focus:ring-[#1565c0] transition-all cursor-pointer">
                        </div>
                        <span class="text-[13px] font-bold text-[#334155] group-hover:text-[#0f172a] transition-colors">Deduct Sales Tax from Profit</span>
                    </label>
                </div>

                <div class="flex gap-2 ml-auto" style="flex-shrink: 0;">
                    <button type="submit" class="btn-brand px-6 h-[38px] text-[13px] shadow-md">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                    </button>
                    @if(request()->anyFilled(['start_date', 'end_date']) || request('month') !== now()->format('Y-m'))
                        <a href="{{ route('reports.financial') }}" class="btn-ghost px-3 h-[38px] text-[#dc2626] border-none bg-[#fee2e2] hover:bg-[#fecaca] rounded-xl transition-all flex items-center" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>

                <!-- Hidden inputs to preserve custom date range if used previously -->
                @if(request('start_date')) <input type="hidden" name="start_date" value="{{ request('start_date') }}"> @endif
                @if(request('end_date')) <input type="hidden" name="end_date" value="{{ request('end_date') }}"> @endif
            </form>
        </div>

        <!-- Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Revenue -->
            <div class="card-c p-5 border-l-4 border-l-[#3b82f6] relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity transform translate-x-4 -translate-y-4">
                    <i class="bi bi-receipt text-8xl text-[#3b82f6]"></i>
                </div>
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1 relative z-10">Total Revenue (Billed)</p>
                <div class="text-2xl font-bold text-[#0f172a] relative z-10">Rs. {{ number_format($totalRevenue, 2) }}</div>
            </div>

            <!-- Received -->
            <div class="card-c p-5 border-l-4 border-l-[#10b981] relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity transform translate-x-4 -translate-y-4">
                    <i class="bi bi-cash-coin text-8xl text-[#10b981]"></i>
                </div>
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1 relative z-10">Total Received (Cash In)</p>
                <div class="text-2xl font-bold text-[#10b981] relative z-10">Rs. {{ number_format($totalReceived, 2) }}</div>
            </div>

            <!-- Pending -->
            <div class="card-c p-5 border-l-4 border-l-[#f59e0b] relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity transform translate-x-4 -translate-y-4">
                    <i class="bi bi-hourglass-split text-8xl text-[#f59e0b]"></i>
                </div>
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1 relative z-10">Total Pending (Unpaid)</p>
                <div class="text-2xl font-bold text-[#f59e0b] relative z-10">Rs. {{ number_format($totalPending, 2) }}</div>
            </div>

            <!-- Expenses -->
            <div class="card-c p-5 border-l-4 border-l-[#ef4444] relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity transform translate-x-4 -translate-y-4">
                    <i class="bi bi-wallet2 text-8xl text-[#ef4444]"></i>
                </div>
                <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1 relative z-10">Total Expenses</p>
                <div class="text-2xl font-bold text-[#ef4444] relative z-10">Rs. {{ number_format($totalExpenses, 2) }}</div>
            </div>

        </div>

        <!-- Net Profit Card -->
        <div class="card-c overflow-hidden mt-6">
            <div class="p-8 {{ $netProfit >= 0 ? 'bg-gradient-to-br from-[#f0fdf4] to-[#ecfdf5]' : 'bg-gradient-to-br from-[#fef2f2] to-[#fee2e2]' }} border-b {{ $netProfit >= 0 ? 'border-[#d1fae5]' : 'border-[#fecaca]' }}">
                
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-8 h-8 rounded-xl {{ $netProfit >= 0 ? 'bg-[#10b981] text-white' : 'bg-[#ef4444] text-white' }} grid place-items-center shadow-sm">
                                <i class="bi {{ $netProfit >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                            </span>
                            <h2 class="text-sm font-bold text-[#0f172a] uppercase tracking-widest">Final Net Profit</h2>
                        </div>
                        
                        <div class="text-4xl md:text-5xl font-black {{ $netProfit >= 0 ? 'text-[#047857]' : 'text-[#b91c1c]' }} tracking-tight">
                            {{ $netProfit >= 0 ? '+' : '-' }} Rs. {{ number_format(abs($netProfit), 2) }}
                        </div>
                        
                        <div class="mt-3 text-[13px] font-medium {{ $netProfit >= 0 ? 'text-[#059669]' : 'text-[#dc2626]' }} flex items-center gap-2">
                            <i class="bi bi-info-circle"></i>
                            Formula: Total Received - Total Expenses {{ $deductTax ? '- Sales Tax' : '' }}
                        </div>
                    </div>

                    <!-- Flow Bar Visualization -->
                    <div class="w-full md:w-1/3 bg-white p-4 rounded-2xl shadow-sm border border-[#e5e9f2]">
                        <div class="flex justify-between text-xs font-bold text-[#64748b] mb-2 uppercase tracking-widest">
                            <span>Inflow</span>
                            <span>Outflow</span>
                        </div>
                        
                        @php
                            $totalOutflow = $totalExpenses + ($deductTax ? $totalSalesTax : 0);
                            $maxScale = max($totalReceived, $totalOutflow) > 0 ? max($totalReceived, $totalOutflow) : 1;
                            $inflowPct = ($totalReceived / $maxScale) * 100;
                            $outflowPct = ($totalOutflow / $maxScale) * 100;
                        @endphp

                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-sm font-bold text-[#0f172a] mb-1">
                                    <span>Received</span>
                                    <span class="text-[#10b981]">Rs. {{ number_format($totalReceived) }}</span>
                                </div>
                                <div class="w-full bg-[#f1f5f9] rounded-full h-2">
                                    <div class="bg-[#10b981] h-2 rounded-full transition-all duration-1000" style="width: {{ $inflowPct }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-bold text-[#0f172a] mb-1">
                                    <span>Expenses {{ $deductTax ? '& Tax' : '' }}</span>
                                    <span class="text-[#ef4444]">Rs. {{ number_format($totalOutflow) }}</span>
                                </div>
                                <div class="w-full bg-[#f1f5f9] rounded-full h-2">
                                    <div class="bg-[#ef4444] h-2 rounded-full transition-all duration-1000" style="width: {{ $outflowPct }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            @if($deductTax)
            <div class="p-4 bg-[#f8fafc] border-t border-[#e5e9f2] flex items-center justify-between text-[13px]">
                <div class="font-semibold text-[#64748b] flex items-center gap-2">
                    <i class="bi bi-shield-check text-[#1565c0]"></i> Sales Tax Deduction Applied
                </div>
                <div class="font-bold text-[#0f172a]">
                    Rs. {{ number_format($totalSalesTax, 2) }} Deducted
                </div>
            </div>
            @endif
        </div>

    </div>

    <style>
        @media print {
            aside, nav, header, form, .print\:hidden, .btn-soft { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            main { padding: 0 !important; max-width: 100% !important; }
            .card-c { border: 1px solid #e5e9f2 !important; box-shadow: none !important; margin-bottom: 20px; page-break-inside: avoid; }
        }
    </style>
</x-admin-layout>
