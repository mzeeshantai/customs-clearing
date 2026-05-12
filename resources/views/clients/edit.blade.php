<x-admin-layout>
    <x-slot name="header">
        Edit Client
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded shadow-md border-t-4 border-indigo-600 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Edit Client Information</h3>
                    <a href="{{ route('clients.index') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-wider">Cancel & Go Back</a>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Company / Client Name</label>
                        <input type="text" name="name" required value="{{ old('name', $client->name) }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">NTN Number</label>
                        <input type="text" name="ntn_no" value="{{ old('ntn_no', $client->ntn_no) }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sales Tax Reg No</label>
                        <input type="text" name="sales_tax_reg_no" value="{{ old('sales_tax_reg_no', $client->sales_tax_reg_no) }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Business Address</label>
                        <textarea name="address" rows="3" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $client->address) }}</textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded shadow-md hover:bg-indigo-700 transition-all">
                        Update Client
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
