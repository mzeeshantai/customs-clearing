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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
            .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
            .content-wrapper { background-color: #f8fafc; }
            
            /* Custom Table Scrollbar */
            .overflow-x-auto::-webkit-scrollbar { height: 6px; }
            .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; }
            .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        </style>
    </head>
    <body class="bg-[#f8fafc] text-slate-900 antialiased overflow-hidden selection:bg-indigo-100 selection:text-indigo-700">
        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
            
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden" x-cloak></div>

            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 z-50 transform w-[260px] lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <x-sidebar />
            </aside>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden content-wrapper">
                
                <!-- Navbar -->
                <nav class="h-[64px] bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm shadow-slate-200/20">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-500 hover:bg-slate-50 rounded-xl transition-all active:scale-95 lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div class="hidden md:flex items-center space-x-6 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                            <a href="#" class="hover:text-indigo-600 transition-colors">Contact</a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Notifications -->
                        <div class="relative group">
                            <button class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span class="absolute top-2 right-2 h-4 w-4 bg-amber-500 text-[10px] font-black text-white flex items-center justify-center rounded-full border-2 border-white shadow-sm shadow-amber-200">15</span>
                            </button>
                        </div>

                        <!-- Fullscreen -->
                        <button class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </button>

                        <div class="h-8 w-px bg-slate-100 mx-2"></div>

                        <!-- Profile Dropdown (Modern Polish) -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-3 px-3 py-1.5 hover:bg-slate-50 rounded-xl transition-all group">
                                <div class="hidden sm:flex flex-col items-end leading-tight">
                                    <span class="text-[13px] font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</span>
                                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mt-0.5">{{ Auth::user()->role ?? 'Super Admin' }}</span>
                                </div>
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-50 to-white flex items-center justify-center text-sm font-bold text-indigo-600 border border-indigo-100/50 shadow-sm group-hover:shadow group-hover:scale-105 transition-all">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>

                            <div x-show="open" x-cloak 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 class="absolute right-0 mt-3 w-60 bg-white border border-slate-200/60 rounded-2xl shadow-2xl shadow-slate-200/40 py-2.5 z-50">
                                <div class="px-5 py-3 border-b border-slate-50 mb-1.5">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">Account Settings</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-5 py-2.5 text-[13px] font-semibold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                    <div class="p-1.5 bg-slate-50 rounded-lg mr-3 group-hover:bg-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    Your Profile
                                </a>
                                <div class="border-t border-slate-50 my-1.5"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-5 py-2.5 text-[13px] font-semibold text-rose-500 hover:bg-rose-50 transition-all">
                                        <div class="p-1.5 bg-rose-50 rounded-lg mr-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        </div>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Main Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 sm:p-8 custom-scrollbar">
                    <x-flash-messages />
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
