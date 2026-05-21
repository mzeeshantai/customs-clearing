<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('employees.index') }}" class="w-10 h-10 rounded-xl bg-white border border-[#e5e9f2] text-[#64748b] hover:text-[#0f172a] hover:border-[#cbd5e1] grid place-items-center transition-all shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Add New Employee</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Register a new staff member</p>
            </div>
        </div>

        <x-flash-messages />

        <div class="card-c max-w-4xl">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="p-6 md:p-8 space-y-8">
                    
                    <!-- Basic Details -->
                    <div>
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Basic Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input-c" required placeholder="John Doe">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Designation <span class="text-red-500">*</span></label>
                                <input type="text" name="designation" value="{{ old('designation') }}" class="form-input-c" required placeholder="e.g., Accountant, Manager">
                                <x-input-error :messages="$errors->get('designation')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-input-c" placeholder="Optional">
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Employment Details -->
                    <div>
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Employment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Joining Date <span class="text-red-500">*</span></label>
                                <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" class="form-input-c" required>
                                <x-input-error :messages="$errors->get('joining_date')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Monthly Salary (Rs.) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="monthly_salary" value="{{ old('monthly_salary') }}" class="form-input-c" required placeholder="0.00">
                                <x-input-error :messages="$errors->get('monthly_salary')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="form-input-c" required>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 md:px-8 bg-[#f8fafc] border-t border-[#e5e9f2] flex items-center justify-end gap-3">
                    <a href="{{ route('employees.index') }}" class="btn-c outline px-6 py-2.5">Cancel</a>
                    <button type="submit" class="btn-brand px-8 py-2.5">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
