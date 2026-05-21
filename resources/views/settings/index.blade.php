<x-admin-layout>
    <div class="space-y-6" x-data="{ 
        showAddModal: false, 
        showEditModal: false,
        editData: { id: '', port_name: '', amount: '' },
        openEdit(id, name, amount) {
            this.editData = { id: id, port_name: name, amount: amount };
            this.showEditModal = true;
        }
    }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Agency Configuration</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Manage global defaults, company identity and billing rates</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge-c warning py-1.5 px-3 uppercase tracking-widest text-[10px]">Administrative Access Only</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Navigation Sidebar (Internal) -->
            <div class="lg:col-span-3 space-y-1">
                <div class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest px-3 mb-2">Configuration Groups</div>
                @foreach($settings as $group => $items)
                    @if($group !== 'cartage')
                        <a href="#group-{{ $group }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $loop->first ? 'bg-[#1565c0] text-white shadow-lg' : 'text-[#64748b] hover:bg-white hover:text-[#0f172a]' }}">
                            <i class="bi bi-{{ $group == 'agency' ? 'building' : 'sliders' }}"></i>
                            <span class="text-[13px] font-bold uppercase tracking-widest">{{ str_replace('_', ' ', $group) }}</span>
                        </a>
                    @endif
                @endforeach
                
                <div class="pt-4 pb-2 px-3">
                    <div class="h-px bg-[#e5e9f2] w-full"></div>
                </div>

                <a href="#group-cartage" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#64748b] hover:bg-white hover:text-[#0f172a] transition-all">
                    <i class="bi bi-truck-flatbed"></i>
                    <span class="text-[13px] font-bold uppercase tracking-widest">Cartage Ports</span>
                </a>
            </div>

            <!-- Forms Container -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Dynamic Cartage Management (Standalone Section) -->
                <div id="group-cartage" class="card-c overflow-hidden transition-all scroll-mt-20">
                    <div class="card-head bg-[#f8fafc]/50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white border border-[#e5e9f2] grid place-items-center text-[#1565c0] shadow-sm">
                                <i class="bi bi-truck text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-[14px] font-bold text-[#0f172a] uppercase tracking-widest">Cartage Management <span class="text-[10px] bg-indigo-50 px-2 py-0.5 rounded text-indigo-400">Total: {{ $cartageCount }}</span></h3>
                                <p class="text-[11px] text-[#64748b] font-medium">Manage dynamic ports and their respective rates</p>
                            </div>
                        </div>
                        <button type="button" @click="showAddModal = true" class="btn-brand py-2 px-4 text-[11px] uppercase tracking-widest border-none">
                            <i class="bi bi-plus-lg me-1"></i> Add Cartage
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-c">
                            <thead>
                                <tr>
                                    <th>Port Name</th>
                                    <th class="text-right">Amount (PKR)</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#f1f5f9]">
                                @forelse($cartages as $cartage)
                                    <tr class="hover:bg-[#f8fafc]">
                                        <td><div class="font-bold text-[#0f172a] uppercase tracking-tight">{{ $cartage->port_name }}</div></td>
                                        <td class="text-right font-black text-[#1565c0]">{{ number_format($cartage->amount, 2) }}</td>
                                        <td class="text-center text-[#64748b] text-[11px] font-medium">{{ $cartage->created_at->format('d M, Y') }}</td>
                                        <td class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="openEdit('{{ $cartage->id }}', '{{ $cartage->port_name }}', '{{ $cartage->amount }}')" 
                                                    class="w-8 h-8 rounded-lg bg-white border border-[#e5e9f2] text-[#1565c0] hover:bg-[#1565c0] hover:text-white transition-all grid place-items-center shadow-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form action="{{ route('cartages.destroy', $cartage) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this cartage entry?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-[#e5e9f2] text-rose-500 hover:bg-rose-500 hover:text-white transition-all grid place-items-center shadow-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-20 text-[#64748b] bg-[#f8fafc]/30">
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <i class="bi bi-truck-flatbed text-5xl mb-4"></i>
                                                <div class="font-black uppercase tracking-widest text-[13px]">No Ports Configured</div>
                                                <p class="text-[11px] mt-2 normal-case font-medium">Add your first cartage port to start using dynamic rates.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Static Settings Form -->
                <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @foreach($settings as $group => $items)
                        @if($group !== 'cartage')
                            <div id="group-{{ $group }}" class="card-c overflow-hidden transition-all scroll-mt-20">
                                <div class="card-head bg-[#f8fafc]/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-[#e5e9f2] grid place-items-center text-[#1565c0] shadow-sm">
                                            <i class="bi bi-{{ $group == 'agency' ? 'building' : 'sliders' }} text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-[14px] font-bold text-[#0f172a] uppercase tracking-widest">{{ str_replace('_', ' ', $group) }} Settings</h3>
                                            <p class="text-[11px] text-[#64748b] font-medium">Update {{ $group }} related parameters</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="grid-2-cols">
                                        @foreach($items as $setting)
                                            <div class="space-y-1">
                                                <label class="form-label-c ml-1">{{ str_replace(['agency_', '_'], ['', ' '], $setting->key) }}</label>
                                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-input-c">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Submit Bar -->
                    <div class="card-c p-4 bg-[#0b1f3a] border-none flex flex-col md:flex-row items-center justify-between gap-4 shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/60">
                                <i class="bi bi-info-circle text-xl"></i>
                            </div>
                            <p class="text-[12px] text-[#cfd8e8] max-w-[340px]">Global changes will be applied instantly across all billing and reporting modules.</p>
                        </div>
                        <button type="submit" class="w-full md:w-auto btn-brand border-none px-10 py-3 text-[12px] uppercase tracking-[0.2em]">
                            <i class="bi bi-check2-circle me-2"></i> Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modals moved to bottom of scope -->
        <!-- Add Modal -->
        <div x-show="showAddModal" x-cloak class="modal-overlay-c">
            <div class="modal-content-c" @click.away="showAddModal = false">
                <div class="modal-header-c">
                    <h3 class="font-black text-[#0f172a] uppercase tracking-widest text-sm">Add New Cartage</h3>
                    <button type="button" @click="showAddModal = false" class="text-[#64748b] hover:text-[#0f172a]"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('cartages.store') }}" method="POST" class="modal-body-c space-y-5">
                    @csrf
                    <div>
                        <label class="form-label-c">Port Name</label>
                        <input type="text" name="port_name" required placeholder="e.g. KICT, Port Qasim" class="form-input-c">
                    </div>
                    <div>
                        <label class="form-label-c">Cartage Amount (PKR)</label>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00" class="form-input-c">
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full btn-brand py-3 text-[12px] uppercase tracking-widest border-none">
                            Save Cartage Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-cloak class="modal-overlay-c">
            <div class="modal-content-c" @click.away="showEditModal = false">
                <div class="modal-header-c">
                    <h3 class="font-black text-[#0f172a] uppercase tracking-widest text-sm">Edit Cartage Entry</h3>
                    <button type="button" @click="showEditModal = false" class="text-[#64748b] hover:text-[#0f172a]"><i class="bi bi-x-lg"></i></button>
                </div>
                <form :action="'{{ url('cartages') }}/' + editData.id" method="POST" class="modal-body-c space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label-c">Port Name</label>
                        <input type="text" name="port_name" x-model="editData.port_name" required class="form-input-c">
                    </div>
                    <div>
                        <label class="form-label-c">Cartage Amount (PKR)</label>
                        <input type="number" step="0.01" name="amount" x-model="editData.amount" required class="form-input-c">
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full btn-brand py-3 text-[12px] uppercase tracking-widest border-none">
                            Update Cartage Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
