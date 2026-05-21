<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Client Registry</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Manage and track all business partners and their profiles</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('clients.create') }}" class="btn-brand py-2 text-[13px]"><i class="bi bi-person-plus me-1"></i> Register Client</a>
            </div>
        </div>

        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>NTN / Tax Registration</th>
                            <th>Contact Information</th>
                            <th>Business Address</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#f1f5f9] text-[#1565c0] grid place-items-center font-bold text-sm">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        <div class="font-bold text-[#0f172a]">{{ $client->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-semibold text-[#334155]">NTN: {{ $client->ntn_no ?: 'N/A' }}</div>
                                    <div class="text-[11px] text-[#64748b] font-medium uppercase tracking-tighter">ST: {{ $client->sales_tax_reg_no ?: 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="text-[13px] text-[#334155]">{{ $client->email ?: 'N/A' }}</div>
                                    <div class="text-[11px] text-[#64748b] font-bold">{{ $client->phone ?: 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="text-[12px] text-[#64748b] max-w-xs truncate" title="{{ $client->address }}">
                                        {{ $client->address }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('clients.edit', $client) }}" class="w-8 h-8 rounded-lg grid place-items-center text-[#64748b] hover:bg-[#f8fafc] transition-all" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg grid place-items-center text-[#dc2626] hover:bg-[#fee2e2] transition-all" title="Delete" onclick="return confirm('Are you sure you want to delete this client?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-20 text-[#64748b] bg-[#f8fafc]/50">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-[#f1f5f9] rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-people text-3xl text-[#cbd5e1]"></i>
                                        </div>
                                        <div class="font-bold">No clients registered yet</div>
                                        <div class="text-sm mt-1">Start by adding your first business partner</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($clients->hasPages())
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e5e9f2]">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
