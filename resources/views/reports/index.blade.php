<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0f172a]">Intelligence & Reports</h1>
            <p class="text-[#64748b] text-[14px] mt-1">Deep dive into financial performance and client analytics</p>
        </div>

        <div class="grid-3-cols gap-6">
            <!-- Billing Report -->
            <a href="{{ route('reports.billing') }}" class="card-c p-6 group hover:border-[#1565c0] transition-all duration-300">
                <div class="flex items-start justify-between mb-8">
                    <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] grid place-items-center text-[#64748b] group-hover:text-[#1565c0] transition-colors">
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-[#0f172a]">Billing Summary</h3>
                <p class="text-[#64748b] text-[13px] mt-1 leading-relaxed">Analyze revenue, agency commissions, and total billed amounts across different periods.</p>
                <div class="mt-4 pt-4 border-t border-[#e5e9f2] flex items-center gap-2">
                    <span class="badge-c info">Financial</span>
                    <span class="badge-c">Exportable</span>
                </div>
            </a>

            <!-- Sales Tax Report -->
            <a href="{{ route('reports.sales-tax') }}" class="card-c p-6 group hover:border-[#1565c0] transition-all duration-300">
                <div class="flex items-start justify-between mb-8">
                    <div class="stat-icon purple" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;"><i class="bi bi-file-earmark-spreadsheet"></i></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] grid place-items-center text-[#64748b] group-hover:text-[#1565c0] transition-colors">
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-[#0f172a]">Party Wise Sales Tax</h3>
                <p class="text-[#64748b] text-[13px] mt-1 leading-relaxed">View detailed sales tax summary grouped by party, formatted for FBR reporting.</p>
                <div class="mt-4 pt-4 border-t border-[#e5e9f2] flex items-center gap-2">
                    <span class="badge-c" style="background: rgba(147, 51, 234, 0.1); color: #9333ea; border-color: rgba(147, 51, 234, 0.2);">Taxation</span>
                    <span class="badge-c">FBR Format</span>
                </div>
            </a>

            <!-- Outstanding Balances -->
            @if(auth()->user()->isAdmin())
            <a href="{{ route('reports.outstanding') }}" class="card-c p-6 group hover:border-[#1565c0] transition-all duration-300">
                <div class="flex items-start justify-between mb-8">
                    <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] grid place-items-center text-[#64748b] group-hover:text-[#1565c0] transition-colors">
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-[#0f172a]">Outstanding Balances</h3>
                <p class="text-[#64748b] text-[13px] mt-1 leading-relaxed">Track unpaid bills and monitor client debt aging to improve recovery cycles.</p>
                <div class="mt-4 pt-4 border-t border-[#e5e9f2] flex items-center gap-2">
                    <span class="badge-c danger">Urgent</span>
                    <span class="badge-c">Recovery</span>
                </div>
            </a>
            @endif

            <!-- Client Analytics -->
            @if(auth()->user()->isAdmin())
            <a href="{{ route('reports.clients') }}" class="card-c p-6 group hover:border-[#1565c0] transition-all duration-300">
                <div class="flex items-start justify-between mb-8">
                    <div class="stat-icon green"><i class="bi bi-people"></i></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] grid place-items-center text-[#64748b] group-hover:text-[#1565c0] transition-colors">
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-[#0f172a]">Client Performance</h3>
                <p class="text-[#64748b] text-[13px] mt-1 leading-relaxed">Identify your top-performing clients by volume and revenue contribution.</p>
                <div class="mt-4 pt-4 border-t border-[#e5e9f2] flex items-center gap-2">
                    <span class="badge-c success">Growth</span>
                    <span class="badge-c">Analytics</span>
                </div>
            </a>
            @endif

            <!-- Financial Summary -->
            <a href="{{ route('reports.financial') }}" class="card-c p-6 group hover:border-[#1565c0] transition-all duration-300">
                <div class="flex items-start justify-between mb-8">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="bi bi-piggy-bank"></i></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] grid place-items-center text-[#64748b] group-hover:text-[#1565c0] transition-colors">
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-[#0f172a]">Financial Summary</h3>
                <p class="text-[#64748b] text-[13px] mt-1 leading-relaxed">View overall business flow, net profits, and unified expense calculations.</p>
                <div class="mt-4 pt-4 border-t border-[#e5e9f2] flex items-center gap-2">
                    <span class="badge-c" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2);">Profit/Loss</span>
                    <span class="badge-c">Monthly</span>
                </div>
            </a>
        </div>
    </div>
</x-admin-layout>
