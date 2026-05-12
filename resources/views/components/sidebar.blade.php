<div class="flex flex-col h-full bg-[#0f172a] text-slate-400 w-[260px] shadow-2xl overflow-hidden border-r border-slate-800">
    <!-- Brand Logo Section -->
    <div class="flex items-center h-[72px] px-7 bg-[#0f172a] border-b border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3.5 group">
            <div class="flex-shrink-0 w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-all duration-300">
                <x-application-logo class="h-5 w-5 fill-current text-white" />
            </div>
            <div class="flex flex-col">
                <span class="text-[14px] font-bold tracking-tight text-white leading-tight">Customs<span class="text-indigo-400 ml-0.5">ERP</span></span>
                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.1em] mt-0.5">Clearing System</span>
            </div>
        </a>
    </div>

    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto custom-scrollbar py-6">
        <!-- User Context (Modern SaaS Style) -->
        <div class="px-4 mb-8">
            <div class="p-3 bg-slate-800/30 rounded-2xl border border-slate-700/40 hover:bg-slate-800/60 hover:border-indigo-500/30 transition-all cursor-pointer group">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-700 flex items-center justify-center text-sm font-bold text-white shadow-inner border border-white/5">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-[13px] font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <div class="flex items-center space-x-1.5 mt-0.5">
                            <span class="h-1.5 w-1.5 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Active Now</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="px-3 space-y-1">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                icon='<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'>
                Dashboard
            </x-sidebar-link>

            <div class="pt-6 pb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] opacity-60">Main Menu</div>

            <div class="space-y-0.5">
                <x-sidebar-link :href="route('clients.index')" :active="request()->routeIs('clients.*')"
                    icon='<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'>
                    Clients
                </x-sidebar-link>

                <x-sidebar-link :href="route('bills.index')" :active="request()->routeIs('bills.*')"
                    icon='<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'>
                    Bills Management
                </x-sidebar-link>

                <x-sidebar-link :href="route('reports.index')" :active="request()->routeIs('reports.*')"
                    icon='<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'>
                    Reports
                </x-sidebar-link>
            </div>

            @if(Auth::user()->isAdmin())
                <div class="pt-8 pb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] opacity-60">System</div>
                <x-sidebar-link :href="route('settings.index')" :active="request()->routeIs('settings.index')"
                    icon='<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'>
                    Agency & Settings
                </x-sidebar-link>
            @endif

            <div class="pt-8 pb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] opacity-60">Account</div>
            <x-sidebar-link :href="route('logout')" :active="false" method="POST"
                icon='<svg class="w-[18px] h-[18px] text-rose-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>'>
                Sign Out
            </x-sidebar-link>
        </nav>
    </div>
</div>
