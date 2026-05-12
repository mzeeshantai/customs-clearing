<x-admin-layout>
    <div class="flex flex-col space-y-8 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight italic">System Configuration</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1">Manage your agency profile, cartage rates, and billing defaults.</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-xl border border-indigo-100 uppercase tracking-widest">
                    Admin Exclusive
                </span>
            </div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Navigation Tabs (Visual Only for now) -->
                <div class="lg:col-span-3">
                    <nav class="space-y-1">
                        @foreach($settings as $group => $items)
                            <a href="#group-{{ $group }}" class="flex items-center px-4 py-3 text-[13px] font-bold rounded-xl transition-all {{ $loop->first ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-100' }}">
                                <span class="uppercase tracking-widest">{{ str_replace('_', ' ', $group) }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Settings Forms -->
                <div class="lg:col-span-9 space-y-8">
                    @foreach($settings as $group => $items)
                        <div id="group-{{ $group }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        @if($group == 'agency')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        @elseif($group == 'cartage')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1zM13 16l5 2V7l-5 2m0 7h5m-5-7h5"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                        @endif
                                    </div>
                                    <h3 class="text-[13px] font-black text-slate-700 uppercase tracking-widest">{{ str_replace('_', ' ', $group) }} Profile</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                    @foreach($items as $setting)
                                        <div class="space-y-1.5">
                                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em] ml-1">{{ str_replace(['agency_', '_'], ['', ' '], $setting->key) }}</label>
                                            <div class="relative group">
                                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" 
                                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between p-6 bg-slate-800 rounded-2xl shadow-xl shadow-slate-200/50">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-slate-700 rounded-lg text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium max-w-[300px]">Changes applied here will affect business reports and system defaults globally.</p>
                        </div>
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-indigo-500 hover:bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95 group">
                            <svg class="w-4 h-4 mr-2.5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Commit Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
