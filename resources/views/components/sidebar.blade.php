<div class="flex flex-col h-full bg-[#0b1f3a] text-[#cfd8e8] w-[260px]">
    <!-- Brand Logo Section (Reference style) -->
    <div class="flex items-center gap-3 px-5 py-4 border-b border-white/5">
        <div class="w-10 h-10 rounded-[10px] bg-gradient-to-br from-[#1e88e5] to-[#0b3d91] grid place-items-center text-white font-bold shadow-[inset_0_-2px_0_rgba(0,0,0,0.15)]">
            C
        </div>
        <div>
            <div class="text-white font-bold tracking-tight text-[15px]">ClearPort</div>
            <div class="side-label font-bold text-[10px] uppercase tracking-widest leading-none">Customs Suite</div>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-6">
        
        <!-- Navigation Section -->
        <div>
            <div class="side-section-title">Main Overview</div>
            <nav class="space-y-0.5">
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                    icon='<i class="bi bi-grid-1x2"></i>'>
                    Dashboard
                </x-sidebar-link>
            </nav>
        </div>

        <div>
            <div class="side-section-title">Core Operations</div>
            <nav class="space-y-0.5">
                <x-sidebar-link :href="route('clients.index')" :active="request()->routeIs('clients.*')"
                    icon='<i class="bi bi-people"></i>'>
                    Client Registry
                </x-sidebar-link>

                <x-sidebar-link :href="route('bills.index')" :active="request()->routeIs('bills.*')"
                    icon='<i class="bi bi-receipt-cutoff"></i>'>
                    Bill Management
                </x-sidebar-link>
            </nav>
        </div>

        <div>
            <div class="side-section-title">Expense Management</div>
            <nav class="space-y-0.5">
                <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')"
                    icon='<i class="bi bi-wallet2"></i>'>
                    All Expenses
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('expense-categories.index')" :active="request()->routeIs('expense-categories.*')"
                    icon='<i class="bi bi-tags"></i>'>
                    Expense Categories
                </x-sidebar-link>

                <x-sidebar-link :href="route('reports.expenses')" :active="request()->routeIs('reports.expenses')"
                    icon='<i class="bi bi-file-earmark-bar-graph"></i>'>
                    Expense Reports
                </x-sidebar-link>
            </nav>
        </div>
        <div>
            <div class="side-section-title">HR & Payroll</div>
            <nav class="space-y-0.5">
                <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')"
                    icon='<i class="bi bi-people"></i>'>
                    Employees
                </x-sidebar-link>
            </nav>
        </div>

        <div>
            <div class="side-section-title">Analytics</div>
            <nav class="space-y-0.5">

                <x-sidebar-link :href="route('reports.index')" :active="request()->routeIs('reports.*')"
                    icon='<i class="bi bi-graph-up-arrow"></i>'>
                    Analytics & Reports
                </x-sidebar-link>
            </nav>
        </div>

        @if(Auth::user()->isAdmin())
            <div>
                <div class="side-section-title">Administration</div>
                <nav class="space-y-0.5">
                    <x-sidebar-link :href="route('settings.index')" :active="request()->routeIs('settings.index')"
                        icon='<i class="bi bi-gear"></i>'>
                        System Settings
                    </x-sidebar-link>
                </nav>
            </div>
        @endif
    </div>

    <!-- Sidebar Footer (Reference style) -->
    <div class="p-4 border-t border-white/5 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-[#1565c0] text-white grid place-items-center font-bold text-sm">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="text-white text-[13px] font-semibold truncate">{{ Auth::user()->name }}</div>
            <div class="text-[#7d93b6] text-[11px] truncate">Karachi Port Office</div>
        </div>
    </div>
</div>
