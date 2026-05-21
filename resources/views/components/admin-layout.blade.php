<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Customs Clearing System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --brand: #0b3d91;
                --brand-2: #1565c0;
                --brand-soft: #eaf2ff;
                --ink: #0f172a;
                --muted: #64748b;
                --bg: #f4f6fb;
                --card: #ffffff;
                --border: #e5e9f2;
                --success: #16a34a;
                --danger: #dc2626;
                --shadow: 0 1px 3px rgba(15,23,42,0.08), 0 10px 30px rgba(15,23,42,0.06);
            }

            body { 
                font-family: 'Inter', system-ui, -apple-system, sans-serif; 
                background-color: var(--bg);
                color: var(--ink);
                margin: 0;
                font-size: 14px;
                -webkit-font-smoothing: antialiased;
            }

            [x-cloak] { display: none !important; }

            /* Core Layout Fixes */
            .main-wrapper {
                display: flex;
                height: 100vh;
                overflow: hidden;
            }

            .sidebar-aside {
                width: 260px;
                background: #0b1f3a;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                z-index: 50;
            }

            .content-area {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
                overflow: hidden;
            }

            .topbar-header {
                height: 64px;
                background: white;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 24px;
                flex-shrink: 0;
            }

            .scroll-area {
                flex: 1;
                overflow-y: auto;
                padding: 28px;
            }

            /* Component Classes */
            .card-c {
                background: white;
                border: 1px solid var(--border);
                border-radius: 16px;
                box-shadow: var(--shadow);
                margin-bottom: 24px;
            }

            .card-head {
                padding: 16px 20px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }

            .card-head h5 {
                margin: 0;
                font-size: 15px;
                font-weight: 700;
                color: var(--ink);
            }

            .card-body-c {
                padding: 20px;
            }

            .btn-brand {
                background: var(--brand);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 12px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
            }

            .btn-brand:hover { background: #082e6e; transform: translateY(-1px); }

            .btn-soft {
                background: var(--brand-soft);
                color: var(--brand-2);
                padding: 10px 20px;
                border-radius: 12px;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                font-size: 13px;
            }

            .badge-c {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                border-radius: 100px;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .badge-c.success { background: #dcfce7; color: #166534; }
            .badge-c.danger { background: #fee2e2; color: #991b1b; }
            .badge-c .ind { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

            /* Sidebar Link Styles */
            .nav-link-cust {
                display: flex;
                align-items: center;
                gap: 12px;
                color: #cfd8e8;
                padding: 12px 16px;
                border-radius: 12px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.2s;
                margin-bottom: 4px;
                font-size: 14px;
            }

            .nav-link-cust:hover {
                background: rgba(255,255,255,0.05);
                color: white;
            }

            .nav-link-cust.active {
                background: linear-gradient(to right, rgba(21, 101, 192, 0.25), transparent);
                color: white;
                box-shadow: inset 3px 0 0 #2196f3;
            }

            .nav-link-cust i {
                font-size: 18px;
                width: 20px;
                text-align: center;
                color: #8aa3c8;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-link-cust.active i {
                color: #64b5f6;
            }

            .side-label {
                color: rgba(255, 255, 255, 0.6);
            }

            .side-section-title {
                color: rgba(255, 255, 255, 0.4);
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 0 12px 8px 12px;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 14px;
                display: grid;
                place-items: center;
                background: #eaf2ff;
                color: #1565c0;
                font-size: 22px;
            }
            .stat-icon.green { background: #e7f7ee; color: #15803d; }
            .stat-icon.amber { background: #fff4e0; color: #b45309; }
            .stat-icon.red { background: #fde8e8; color: #b91c1c; }

            /* Dashboard Grid Fix */
            .grid-stats {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 24px;
                margin-bottom: 24px;
            }

            @media (max-width: 1280px) {
                .grid-stats { grid-template-columns: repeat(2, 1fr); }
            }

            @media (max-width: 640px) {
                .grid-stats { grid-template-columns: 1fr; }
            }

            .grid-2-cols {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
            }

            @media (max-width: 1280px) {
                .grid-2-cols { grid-template-columns: 1fr; }
            }

            .grid-3-cols {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 24px;
            }

            @media (max-width: 1280px) {
                .grid-3-cols { grid-template-columns: repeat(2, 1fr); }
            }

            @media (max-width: 768px) {
                .grid-3-cols { grid-template-columns: 1fr; }
            }

            .grid-5-cols {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 20px;
            }

            @media (max-width: 1280px) {
                .grid-5-cols { grid-template-columns: repeat(3, 1fr); }
            }

            @media (max-width: 768px) {
                .grid-5-cols { grid-template-columns: repeat(2, 1fr); }
            }

            @media (max-width: 480px) {
                .grid-5-cols { grid-template-columns: 1fr; }
            }

            .filter-row {
                display: flex;
                align-items: flex-end;
                gap: 12px;
                flex-wrap: nowrap;
            }

            .table-c {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-c thead th {
                text-align: left;
                padding: 12px 16px;
                background: #f8fafc;
                border-bottom: 1px solid var(--border);
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: var(--muted);
            }

            .table-c tbody td {
                padding: 14px 16px;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
                color: var(--ink);
            }

            .table-c tbody tr:hover {
                background-color: #f9fbff;
            }

            /* Modal System */
            .modal-overlay-c {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(4px);
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
            }

            .modal-content-c {
                background: white;
                width: 100%;
                max-width: 480px;
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                overflow: hidden;
                animation: modalSlideUp 0.3s ease-out;
            }

            @keyframes modalSlideUp {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }

            .modal-header-c {
                padding: 20px 24px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .modal-body-c {
                padding: 24px;
            }

            .form-label-c {
                display: block;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: var(--muted);
                margin-bottom: 8px;
            }

            .form-input-c {
                width: 100%;
                padding: 12px 16px;
                background: #f8fafc;
                border: 1px solid var(--border);
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                color: var(--ink);
                transition: all 0.2s;
            }

            .form-input-c:focus {
                outline: none;
                border-color: var(--brand-2);
                background: white;
                box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.1);
            }

            /* Responsive tweaks */
            @media (max-width: 1024px) {
                .sidebar-aside {
                    position: fixed;
                    left: -260px;
                    transition: transform 0.3s ease;
                }
                .sidebar-aside.open {
                    transform: translateX(260px);
                }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="main-wrapper" x-data="{ sidebarOpen: false }">
            
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 z-40 bg-[#0b1f3a]/60 backdrop-blur-sm lg:hidden" x-cloak></div>

            <!-- Sidebar -->
            <aside class="sidebar-aside lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-[260px] open' : ''">
                <x-sidebar />
            </aside>

            <!-- Content Area -->
            <div class="content-area">
                
                <!-- Navbar (Topbar) -->
                <header class="topbar-header">
                    <div class="flex items-center gap-4 flex-1">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-[#64748b] hover:bg-[#f8fafc] rounded-xl lg:hidden">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        
                        <!-- Search Bar -->
                        <div class="hidden md:flex items-center gap-2 bg-[#f1f5fb] border border-transparent rounded-[10px] px-3.5 py-2 w-[340px] focus-within:border-[#1565c040] transition-all">
                            <i class="bi bi-search text-[#64748b]"></i>
                            <input type="text" placeholder="Search anything..." class="bg-transparent border-0 outline-none w-full text-sm placeholder:text-[#64748b]">
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Notifications -->
                        <button class="w-[38px] h-[38px] rounded-[10px] border border-[#e5e9f2] bg-white grid place-items-center text-[#64748b] hover:text-[#0f172a] hover:border-[#cfd8e8] relative transition-all">
                            <i class="bi bi-bell"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-[#dc2626] rounded-full border-2 border-white"></span>
                        </button>

                        <div class="h-6 w-px bg-[#e5e9f2] mx-1"></div>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-3 p-1 hover:bg-[#f8fafc] rounded-xl transition-all group">
                                <div class="w-9 h-9 rounded-full bg-[#1565c0] text-white grid place-items-center font-semibold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden sm:block text-left leading-tight pr-2">
                                    <div class="text-[13px] font-bold text-[#0f172a]">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-[#64748b] font-medium uppercase tracking-wider">{{ Auth::user()->role ?? 'Administrator' }}</div>
                                </div>
                            </button>

                            <div x-show="open" x-cloak 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-52 bg-white border border-[#e5e9f2] rounded-xl shadow-xl py-1.5 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a] transition-all">
                                    <i class="bi bi-person"></i> Profile Settings
                                </a>
                                <div class="border-t border-[#e5e9f2] my-1.5"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-[13px] font-medium text-[#dc2626] hover:bg-[#fee2e2] transition-all text-left">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="scroll-area custom-scrollbar">
                    <x-flash-messages />
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
