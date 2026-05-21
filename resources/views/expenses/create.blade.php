<x-admin-layout>
    <div class="space-y-6" x-data="expenseForm()">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('expenses.index') }}" class="w-10 h-10 rounded-xl bg-white border border-[#e5e9f2] text-[#64748b] hover:text-[#0f172a] hover:border-[#cbd5e1] grid place-items-center transition-all shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Record New Expense</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Add a new operational expense to the system</p>
            </div>
        </div>

        <x-flash-messages />

        <div class="card-c max-w-4xl">
            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 md:p-8 space-y-8">
                    
                    <!-- Category Selection -->
                    <div>
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Category & Type</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Category <span class="text-red-500">*</span></label>
                                <select name="category_name" class="form-input-c" required x-model="categoryName" @change="onCategoryChange()">
                                    <option value="" disabled>Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ old('category_name') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Expense Date <span class="text-red-500">*</span></label>
                                <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" class="form-input-c" required>
                                <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- ============================================= -->
                    <!-- SALARY WORKFLOW (shown when category=Salary)  -->
                    <!-- ============================================= -->
                    <div x-show="isSalary" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">
                            <i class="bi bi-person-badge mr-1 text-[#1565c0]"></i> Employee & Salary Details
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Select Employee <span class="text-red-500">*</span></label>
                                <select name="employee_id" class="form-input-c" x-model="employeeId" @change="fetchEmployee()">
                                    <option value="">Choose employee...</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Salary Month <span class="text-red-500">*</span></label>
                                <input type="date" name="salary_month" value="{{ old('salary_month') }}" class="form-input-c" x-bind:required="isSalary">
                                <x-input-error :messages="$errors->get('salary_month')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Employee Info Card -->
                        <div x-show="empLoaded" x-cloak x-transition class="mb-6 p-4 bg-gradient-to-r from-[#f0f7ff] to-[#f8fafc] border border-[#d4e4f7] rounded-2xl">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-[#1565c0] text-white grid place-items-center font-bold text-sm" x-text="empName ? empName.charAt(0) : ''"></div>
                                <div>
                                    <div class="font-bold text-[#0f172a] text-[15px]" x-text="empName"></div>
                                    <div class="text-[12px] text-[#64748b]" x-text="empDesignation"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-white rounded-xl border border-[#e5e9f2]">
                                    <div class="text-[10px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Fixed Salary</div>
                                    <div class="text-lg font-bold text-[#0f172a]">Rs. <span x-text="formatNum(empSalary)"></span></div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-xl border border-[#e5e9f2]">
                                    <div class="text-[10px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Adjustments</div>
                                    <div class="text-lg font-bold" :class="(parseFloat(bonus||0) - parseFloat(deductionAmt||0)) >= 0 ? 'text-[#16a34a]' : 'text-[#dc2626]'">
                                        <span x-text="((parseFloat(bonus||0) - parseFloat(deductionAmt||0)) >= 0 ? '+' : '') + formatNum(parseFloat(bonus||0) - parseFloat(deductionAmt||0))"></span>
                                    </div>
                                </div>
                                <div class="text-center p-3 bg-[#0b3d91] rounded-xl text-white">
                                    <div class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-80">Final Amount</div>
                                    <div class="text-lg font-bold">Rs. <span x-text="formatNum(finalAmount)"></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Bonus / Deduction -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Bonus (Optional)</label>
                                <input type="number" step="0.01" name="bonus" x-model="bonus" @input="calcFinal()" class="form-input-c" placeholder="0.00" value="{{ old('bonus') }}">
                                <x-input-error :messages="$errors->get('bonus')" class="mt-2" />
                            </div>
                            <div class="form-group-c">
                                <label class="form-label-c">Deduction (Optional)</label>
                                <input type="number" step="0.01" name="deduction" x-model="deductionAmt" @input="calcFinal()" class="form-input-c" placeholder="0.00" value="{{ old('deduction') }}">
                                <x-input-error :messages="$errors->get('deduction')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- ============================================= -->
                    <!-- NORMAL EXPENSE (shown when NOT salary)        -->
                    <!-- ============================================= -->
                    <div x-show="!isSalary" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Expense Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Expense Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-input-c" placeholder="e.g., Office Rent - May 2026" x-bind:required="!isSalary">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            <div class="form-group-c">
                                <label class="form-label-c">Amount (Rs.) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-input-c" placeholder="0.00" x-bind:required="!isSalary">
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details (shared) -->
                    <div>
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Payment & Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group-c">
                                <label class="form-label-c">Payment Method <span class="text-red-500">*</span></label>
                                <select name="payment_method" class="form-input-c" required>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="Online Transfer" {{ old('payment_method') == 'Online Transfer' ? 'selected' : '' }}>Online Transfer</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Reference Number</label>
                                <input type="text" name="reference_no" value="{{ old('reference_no') }}" class="form-input-c" placeholder="Optional">
                                <x-input-error :messages="$errors->get('reference_no')" class="mt-2" />
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="form-input-c" required>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details (shared) -->
                    <div>
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase tracking-widest mb-4 pb-2 border-b border-[#e5e9f2]">Additional Details</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="form-group-c" x-show="!isSalary">
                                <label class="form-label-c">Attachment (Receipt/Invoice)</label>
                                <input type="file" name="attachment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#eff6ff] file:text-[#1565c0] hover:file:bg-[#dbeafe]">
                                <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Max size: 5MB. Formats: JPG, PNG, PDF, DOC</p>
                            </div>

                            <div class="form-group-c">
                                <label class="form-label-c">Notes / Description</label>
                                <textarea name="notes" rows="3" class="form-input-c" placeholder="Optional description...">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 md:px-8 bg-[#f8fafc] border-t border-[#e5e9f2] flex items-center justify-end gap-3">
                    <a href="{{ route('expenses.index') }}" class="btn-c outline px-6 py-2.5">Cancel</a>
                    <button type="submit" class="btn-brand px-8 py-2.5">Save Expense</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function expenseForm() {
            return {
                categoryName: '{{ old("category_name", "") }}',
                isSalary: false,
                employeeId: '{{ old("employee_id", "") }}',
                empName: '',
                empDesignation: '',
                empSalary: 0,
                empLoaded: false,
                bonus: '{{ old("bonus", "") }}',
                deductionAmt: '{{ old("deduction", "") }}',
                finalAmount: 0,

                init() {
                    if (this.categoryName) {
                        this.onCategoryChange();
                    }
                    if (this.employeeId && this.isSalary) {
                        this.fetchEmployee();
                    }
                },

                onCategoryChange() {
                    const name = this.categoryName.toLowerCase().trim();
                    this.isSalary = (name === 'salary' || name === 'salaries & wages');
                    if (!this.isSalary) {
                        this.empLoaded = false;
                        this.employeeId = '';
                    }
                },

                async fetchEmployee() {
                    if (!this.employeeId) {
                        this.empLoaded = false;
                        return;
                    }
                    try {
                        const res = await fetch(`/ajax/employee/${this.employeeId}`);
                        if (!res.ok) throw new Error('Failed');
                        const data = await res.json();
                        this.empName = data.name;
                        this.empDesignation = data.designation;
                        this.empSalary = parseFloat(data.monthly_salary) || 0;
                        this.empLoaded = true;
                        this.calcFinal();
                    } catch (e) {
                        console.error(e);
                        this.empLoaded = false;
                    }
                },

                calcFinal() {
                    const base = parseFloat(this.empSalary) || 0;
                    const b = parseFloat(this.bonus) || 0;
                    const d = parseFloat(this.deductionAmt) || 0;
                    this.finalAmount = base + b - d;
                },

                formatNum(n) {
                    return parseFloat(n || 0).toLocaleString('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
    @endpush
</x-admin-layout>
