<x-admin-layout>
    <x-slot name="header">
        Manage Clients
    </x-slot>

    <div class="bg-white rounded shadow-md border-t-4 border-indigo-600 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Client List</h3>
            <a href="{{ route('clients.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded uppercase hover:bg-indigo-700 transition-colors shadow-sm">
                Add New Client
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-widest border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Client Name</th>
                        <th class="px-6 py-4">NTN / ST Reg</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Address</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $client->name }}</td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-medium text-slate-600">NTN: {{ $client->ntn_no ?: 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400 uppercase">ST: {{ $client->sales_tax_reg_no ?: 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600">{{ $client->email ?: 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $client->phone ?: 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs truncate max-w-xs">{{ $client->address }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('clients.edit', $client) }}" class="text-indigo-500 hover:text-indigo-700 font-bold text-[10px] uppercase">Edit</a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-600 font-bold text-[10px] uppercase" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium italic">
                                No clients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clients->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
