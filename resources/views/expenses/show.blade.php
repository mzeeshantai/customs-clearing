<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('expenses.index') }}" class="w-10 h-10 rounded-xl bg-white border border-[#e5e9f2] text-[#64748b] hover:text-[#0f172a] hover:border-[#cbd5e1] grid place-items-center transition-all shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Expense Details</h1>
                <p class="text-[#64748b] text-[14px] mt-1">EXP-{{ str_pad($expense->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn-c outline py-2 px-4">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="card-c p-6">
                    <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Overview</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Title</p>
                            <p class="font-semibold text-[#0f172a]">{{ $expense->title }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Category</p>
                            <p class="font-semibold text-[#0f172a]">{{ $expense->category->name ?? 'Uncategorized' }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Date</p>
                            <p class="font-semibold text-[#0f172a]">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Amount</p>
                            <p class="text-xl font-bold text-[#0f172a]">Rs. {{ number_format($expense->amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                <div class="card-c p-6">
                    <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Notes</h3>
                    @if($expense->notes)
                        <p class="text-[#334155] whitespace-pre-wrap">{{ $expense->notes }}</p>
                    @else
                        <p class="text-[#94a3b8] italic">No additional notes provided.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <!-- Payment Info -->
                <div class="card-c p-6">
                    <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Payment Info</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Status</p>
                            @if($expense->status === 'paid')
                                <span class="badge-c success py-1 px-3 text-[10px]">Paid</span>
                            @else
                                <span class="badge-c warning py-1 px-3 text-[10px]">Pending</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Method</p>
                            <p class="font-semibold text-[#0f172a]">{{ $expense->payment_method }}</p>
                        </div>
                        @if($expense->reference_no)
                            <div>
                                <p class="text-[#64748b] text-[11px] font-bold uppercase tracking-widest mb-1">Reference Number</p>
                                <p class="font-semibold text-[#0f172a]">{{ $expense->reference_no }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attachment -->
                <div class="card-c p-6">
                    <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Attachment</h3>
                    @if($expense->attachment_path)
                        <div class="flex items-center gap-3 p-3 bg-[#f8fafc] border border-[#e5e9f2] rounded-xl w-full">
                            <i class="bi bi-file-earmark-text text-[#1565c0] text-2xl"></i>
                            <div>
                                <p class="text-[13px] font-bold text-[#0f172a]">Document Available</p>
                                <a href="{{ Storage::url($expense->attachment_path) }}" target="_blank" class="text-[11px] font-bold text-[#1565c0] hover:underline uppercase tracking-widest">View File</a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 bg-[#f8fafc] rounded-xl border border-dashed border-[#cbd5e1]">
                            <i class="bi bi-file-earmark-x text-3xl text-[#cbd5e1] mb-2 block"></i>
                            <p class="text-[#94a3b8] text-sm">No attachment</p>
                        </div>
                    @endif
                </div>
                
                <div class="text-right text-xs text-gray-500">
                    <p>Added by: <strong>{{ $expense->user->name ?? 'System' }}</strong></p>
                    <p>On: {{ $expense->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
