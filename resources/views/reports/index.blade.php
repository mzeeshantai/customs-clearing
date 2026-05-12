<x-admin-layout>
    <x-slot name="header">
        Reports Dashboard
    </x-slot>

    <div class="flex flex-wrap gap-6">
        
        <!-- Billing Report -->
        <a href="{{ route('reports.billing') }}" class="group relative flex flex-col bg-white rounded-3xl border border-slate-100 p-6 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 w-full md:w-[calc(33.333%-16px)] min-w-[280px]">
            <div class="flex items-center justify-between mb-8">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="p-2 bg-slate-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-tight mb-1.5">Billing Summary</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed opacity-80">Revenue & Arrears Analysis</p>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-50 flex items-center text-[10px] font-black text-indigo-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all">
                <span>Access Report</span>
                <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>

        <!-- Outstanding Balances -->
        @if(auth()->user()->isAdmin())
        <a href="{{ route('reports.outstanding') }}" class="group relative flex flex-col bg-white rounded-3xl border border-slate-100 p-6 hover:shadow-xl hover:shadow-rose-500/10 transition-all duration-300 w-full md:w-[calc(33.333%-16px)] min-w-[280px]">
            <div class="flex items-center justify-between mb-8">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="p-2 bg-slate-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-tight mb-1.5">Outstanding</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed opacity-80">Debt & Aging Metrics</p>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-50 flex items-center text-[10px] font-black text-rose-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all">
                <span>Access Report</span>
                <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>
        @endif

        <!-- Client Analytics -->
        @if(auth()->user()->isAdmin())
        <a href="{{ route('reports.clients') }}" class="group relative flex flex-col bg-white rounded-3xl border border-slate-100 p-6 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 w-full md:w-[calc(33.333%-16px)] min-w-[280px]">
            <div class="flex items-center justify-between mb-8">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="p-2 bg-slate-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-tight mb-1.5">Performance</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed opacity-80">Partner Revenue Trends</p>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-50 flex items-center text-[10px] font-black text-emerald-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all">
                <span>Access Report</span>
                <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>
        @endif

    </div>
</x-admin-layout>
