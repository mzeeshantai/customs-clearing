<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-300 antialiased selection:bg-indigo-500/30 selection:text-indigo-200" style="font-family: 'Outfit', sans-serif;">
        <div class="min-h-screen relative flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#0f172a] overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
            
            <div class="relative z-10 w-full flex flex-col items-center">
                <div class="mb-10 transform hover:scale-110 transition-transform duration-500">
                    <a href="/" class="flex flex-col items-center group">
                        <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center shadow-2xl shadow-indigo-600/20 group-hover:rotate-[10deg] transition-all duration-500">
                            <x-application-logo class="w-12 h-12 fill-current text-white" />
                        </div>
                        <div class="mt-4 text-center">
                            <h1 class="text-2xl font-black text-white tracking-tight">SEA <span class="text-indigo-500">PEARL</span></h1>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.3em] mt-1">Clearing & Logistics</p>
                        </div>
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 shadow-2xl overflow-hidden rounded-2xl">
                    {{ $slot }}
                </div>

                <!-- Footer Text -->
                <div class="mt-8 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest opacity-60">
                    &copy; {{ date('Y') }} Sea Pearl Services. All Rights Reserved.
                </div>
            </div>
        </div>
    </body>
</html>
