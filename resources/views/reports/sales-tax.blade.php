<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Party Wise Sales Tax Report</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Detailed sales tax analysis grouped by client</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.sales-tax.print', array_merge(request()->query(), ['type' => 'details'])) }}" target="_blank" class="btn-c outline">
                    <i class="bi bi-printer text-lg"></i>
                    Print Details
                </a>
                <a href="{{ route('reports.sales-tax.print', array_merge(request()->query(), ['type' => 'summary'])) }}" target="_blank" class="btn-c primary">
                    <i class="bi bi-printer text-lg"></i>
                    Print Summary
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-c p-6">
            <form action="{{ route('reports.sales-tax') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                
                <div class="form-group-c">
                    <label class="form-label-c">Month</label>
                    <input type="month" name="month" class="form-input-c" value="{{ request('month') }}">
                </div>

                <div class="form-group-c">
                    <label class="form-label-c">Date Range (Start)</label>
                    <input type="date" name="start_date" class="form-input-c" value="{{ request('start_date') }}">
                </div>
                
                <div class="form-group-c">
                    <label class="form-label-c">Date Range (End)</label>
                    <input type="date" name="end_date" class="form-input-c" value="{{ request('end_date') }}">
                </div>

                <div class="form-group-c">
                    <label class="form-label-c">Client</label>
                    <select name="client_id" class="form-input-c">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-c">
                    <label class="form-label-c">Status</label>
                    <select name="status" class="form-input-c">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <div class="form-group-c">
                    <label class="form-label-c">Search</label>
                    <input type="text" name="search" class="form-input-c" placeholder="NTN, Bill #..." value="{{ request('search') }}">
                </div>

                <div class="form-group-c flex justify-end items-end gap-2 md:col-span-1 lg:col-span-2">
                    <button type="submit" class="btn-brand px-6 h-[38px] text-[13px] shadow-md">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    @if(request()->anyFilled(['month', 'start_date', 'end_date', 'client_id', 'status', 'search']))
                        <a href="{{ route('reports.sales-tax', ['clear_filters' => 1]) }}" class="btn-ghost px-3 h-[38px] text-[#dc2626] border-none bg-[#fee2e2] hover:bg-[#fecaca] rounded-xl transition-all flex items-center" title="Clear Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid-3-cols gap-4">
            <div class="card-c p-5 border-l-4 border-l-[#1565c0]">
                <p class="text-[13px] font-semibold text-[#64748b] uppercase tracking-wider mb-1">Total Invoices</p>
                <div class="text-2xl font-bold text-[#0f172a]">{{ number_format($grandTotalInvoices) }}</div>
            </div>
            <div class="card-c p-5 border-l-4 border-l-[#10b981]">
                <p class="text-[13px] font-semibold text-[#64748b] uppercase tracking-wider mb-1">Total Agency Commission</p>
                <div class="text-2xl font-bold text-[#0f172a]">Rs. {{ number_format($grandTotalAgency, 2) }}</div>
            </div>
            <div class="card-c p-5 border-l-4 border-l-[#f59e0b]">
                <p class="text-[13px] font-semibold text-[#64748b] uppercase tracking-wider mb-1">Total Sales Tax</p>
                <div class="text-2xl font-bold text-[#0f172a]">Rs. {{ number_format($grandTotalSalesTax, 2) }}</div>
            </div>
        </div>

        <!-- Detailed Report Grouped By Party -->
        <div class="space-y-6">
            @forelse($groupedBills as $clientId => $bills)
                @php
                    $client = $bills->first()->client;
                    $clientTotalAgency = $bills->sum('agency_commission');
                    $clientTotalSalesTax = $bills->sum('sales_tax_amount');
                @endphp
                <div class="card-c">
                    <div class="p-4 bg-[#f8fafc] border-b border-[#e5e9f2] flex justify-between items-center rounded-t-[12px]">
                        <div>
                            <h3 class="text-lg font-bold text-[#0f172a]">{{ $client->name }}</h3>
                            <p class="text-sm text-[#64748b]">NTN: {{ $client->ntn_no ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="badge-c bg-white text-[#1565c0] border-[#e5e9f2]">{{ $bills->count() }} Invoices</span>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="table-c">
                            <thead>
                                <tr>
                                    <th class="w-16 text-center">S.No</th>
                                    <th>INV #</th>
                                    <th>DATE</th>
                                    <th>OWNER NAME</th>
                                    <th>AGENCY CGHS</th>
                                    <th class="text-right">S. TAX</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $index => $bill)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="font-medium text-[#1565c0]">{{ $bill->bill_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($bill->date)->format('d/m/Y') }}</td>
                                        <td>{{ $client->owner_name ?? '-' }}</td>
                                        <td>{{ number_format($bill->agency_commission, 2) }}</td>
                                        <td class="text-right font-medium">{{ number_format($bill->sales_tax_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-[#f8fafc]">
                                <tr>
                                    <td colspan="4" class="text-right font-bold text-[#0f172a] py-3 px-4">TOTAL:</td>
                                    <td class="font-bold text-[#0f172a]">{{ number_format($clientTotalAgency, 2) }}</td>
                                    <td class="text-right font-bold text-[#0f172a]">{{ number_format($clientTotalSalesTax, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @empty
                <div class="card-c p-12 text-center">
                    <div class="w-16 h-16 bg-[#f1f5f9] rounded-full flex items-center justify-center mx-auto mb-4 text-[#94a3b8]">
                        <i class="bi bi-inbox text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-[#0f172a]">No data found</h3>
                    <p class="text-[#64748b] mt-1">Try adjusting your filters to find what you're looking for.</p>
                </div>
            @endforelse
            
            @if(count($groupedBills) > 0)
                <div class="card-c overflow-hidden border-t-4 border-l-[#0f172a]">
                    <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#f8fafc]">
                        <h3 class="text-lg font-bold text-[#0f172a] uppercase tracking-widest">Grand Total (Details)</h3>
                        <div class="flex gap-8 sm:gap-12 text-right">
                            <div>
                                <span class="block text-[11px] text-[#64748b] font-bold uppercase mb-1">Total Agency CGHS</span>
                                <span class="text-xl font-bold text-[#0f172a]">Rs. {{ number_format($grandTotalAgency, 2) }}</span>
                            </div>
                            <div>
                                <span class="block text-[11px] text-[#64748b] font-bold uppercase mb-1">Total Sales Tax</span>
                                <span class="text-xl font-bold text-[#0f172a]">Rs. {{ number_format($grandTotalSalesTax, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Grand Summary Table -->
        @if(count($summary) > 0)
        <div class="card-c mt-8">
            <div class="p-4 bg-[#f8fafc] border-b border-[#e5e9f2] rounded-t-[12px]">
                <h3 class="text-lg font-bold text-[#0f172a]">Grand Summary</h3>
            </div>
            <div class="table-container">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th class="w-16 text-center">S.NO</th>
                            <th>IMPORTER NAME</th>
                            <th>SNTN#</th>
                            <th class="text-center">NO OF INVOICES</th>
                            <th class="text-right">SALES TAX VALUE (AGENCY)</th>
                            <th class="text-right">AMOUNT OF SALES TAX</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary as $index => $row)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="font-medium">{{ $row->client_name }}</td>
                                <td>{{ $row->ntn_no }}</td>
                                <td class="text-center">{{ $row->invoice_count }}</td>
                                <td class="text-right">{{ number_format($row->agency_commission, 2) }}</td>
                                <td class="text-right">{{ number_format($row->sales_tax, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-[#f8fafc]">
                        <tr>
                            <td colspan="3" class="text-right font-bold text-[#0f172a] py-3 px-4">GRAND TOTAL:</td>
                            <td class="text-center font-bold text-[#0f172a]">{{ number_format($grandTotalInvoices) }}</td>
                            <td class="text-right font-bold text-[#0f172a]">{{ number_format($grandTotalAgency, 2) }}</td>
                            <td class="text-right font-bold text-[#0f172a]">{{ number_format($grandTotalSalesTax, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-admin-layout>
