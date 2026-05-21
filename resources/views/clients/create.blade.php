<x-admin-layout>
    <x-slot name="header">
        Add New Client
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded shadow-md border-t-4 border-indigo-600 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Client Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Company / Client Name</label>
                        <input type="text" name="name" required class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Owner Name</label>
                        <input type="text" name="owner_name" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">NTN Number</label>
                        <input type="text" name="ntn_no" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sales Tax Reg No</label>
                        <input type="text" name="sales_tax_reg_no" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Business Address</label>
                        <textarea name="address" rows="3" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded shadow-md hover:bg-indigo-700 transition-all">
                        Create Client
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
