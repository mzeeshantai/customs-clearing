<x-admin-layout>
    <x-slot name="header">
        Generate New Bill
    </x-slot>

    <div x-data="billForm()" class="pb-10">
        <form action="{{ route('bills.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: GD Info & Client -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info Card -->
                    <div class="bg-white rounded shadow-md border-t-4 border-indigo-600 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">General Information</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bill Number</label>
                                <input type="text" name="bill_no" required value="{{ old('bill_no', $nextBillNo) }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bill Date</label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Payment Status</label>
                                <select name="status" x-model="paymentStatus" required class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                            <!-- Conditional Payment Method -->
                            <div x-show="paymentStatus === 'paid'" x-transition>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Payment Method <span class="text-rose-500">*</span></label>
                                <select name="payment_method" :required="paymentStatus === 'paid'" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select method...</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="pay_order" {{ old('payment_method') == 'pay_order' ? 'selected' : '' }}>Pay Order</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="online_transfer" {{ old('payment_method') == 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="lg:col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Select Client</label>
                                <select name="client_id" required x-model="selectedClientId" @change="updateClientInfo()" class="w-full text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select a client...</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" data-ntn="{{ $client->ntn_no }}" data-address="{{ $client->address }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="selectedClientId" class="lg:col-span-4 md:col-span-2 p-3 bg-indigo-50 rounded border border-indigo-100 flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase">NTN / Registration</p>
                                    <p class="text-sm font-bold text-indigo-700" x-text="clientNtn || 'N/A'"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase">Client Address</p>
                                    <p class="text-xs text-indigo-600" x-text="clientAddress || 'N/A'"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GD Details Card -->
                    <div class="bg-white rounded shadow-md border-t-4 border-emerald-500 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">GD & Shipment Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">GD Number</label>
                                <input type="text" name="gd_no" required class="w-full text-sm border-slate-200 rounded focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">GD Date</label>
                                <input type="date" name="gd_date" required value="{{ date('Y-m-d') }}" class="w-full text-sm border-slate-200 rounded focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Location / Port</label>
                                <select name="location" x-model="location" @change="calculateCartage()" class="w-full text-sm border-slate-200 rounded focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select location...</option>
                                    @foreach($settings['cartages'] as $port => $amount)
                                        <option value="{{ $port }}">{{ $port }}</option>
                                    @endforeach
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Container Count</label>
                                <input type="number" name="container_count" x-model.number="containerCount" @input="calculateCartage()" min="1" class="w-full text-sm border-slate-200 rounded focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Particulars Card -->
                    <div class="bg-white rounded shadow-md border-t-4 border-amber-500 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Particulars of Charges</h3>
                            <button type="button" @click="addItem()" class="px-3 py-1 bg-amber-500 text-white text-[10px] font-bold rounded uppercase hover:bg-amber-600 transition-colors shadow-sm">+ Add Row</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-widest">
                                    <tr>
                                        <th class="px-6 py-3 w-1/3">Particular Name</th>
                                        <th class="px-6 py-3">Receipt Type</th>
                                        <th class="px-6 py-3">Actual Amt</th>
                                        <th class="px-6 py-3">PO Amount</th>
                                        <th class="px-6 py-3 text-center">Paid by Agent?</th>
                                        <th class="px-6 py-3 text-right">Short</th>
                                        <th class="px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-3">
                                                <input type="text" x-model="item.particular_name" :name="'items['+index+'][particular_name]'" class="w-full text-xs rounded focus:bg-white" :class="item.is_fixed ? 'border-none bg-transparent font-bold text-slate-700 shadow-none' : 'border-slate-100 bg-transparent'" :readonly="item.is_fixed" placeholder="e.g. Wharfage">
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="text" x-model="item.receipt_type" :name="'items['+index+'][receipt_type]'" class="w-full text-xs rounded focus:bg-white" :class="item.is_fixed && item.receipt_type ? 'border-none bg-transparent font-medium text-slate-500 shadow-none' : 'border-slate-100 bg-transparent'" :readonly="item.is_fixed && item.receipt_type !== ''" placeholder="P.Oder">
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="number" x-model.number="item.actual_amount" :name="'items['+index+'][actual_amount]'" @input="calculateItemShort(index)" class="w-24 text-xs border-slate-100 rounded bg-transparent focus:bg-white text-right">
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="number" x-model.number="item.pay_order_amount" :name="'items['+index+'][pay_order_amount]'" @input="calculateItemShort(index)" :disabled="item.is_paid_by_agent" class="w-24 text-xs border-slate-100 rounded bg-transparent focus:bg-white text-right" :class="item.is_paid_by_agent ? 'opacity-30' : ''">
                                            </td>
                                            <td class="px-6 py-3 text-center">
                                                <input type="checkbox" x-model="item.is_paid_by_agent" :name="'items['+index+'][is_paid_by_agent]'" value="1" @change="calculateItemShort(index)" class="rounded text-indigo-600" :disabled="item.is_fixed && !item.is_paid_by_agent && item.receipt_type !== ''">
                                            </td>
                                            <td class="px-6 py-3 text-right font-bold" :class="item.short_payment > 0 ? 'text-rose-500' : 'text-slate-400'" x-text="item.short_payment.toLocaleString()"></td>
                                            <td class="px-6 py-3 text-right">
                                                <button type="button" @click="removeItem(index)" class="text-slate-300 hover:text-rose-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary & Totals -->
                <div class="space-y-6">
                    <div class="bg-white rounded shadow-md border-t-4 border-indigo-600 overflow-hidden sticky top-20">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Bill Summary</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Agency Commission -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Agency Commission</span>
                                    <input type="number" name="agency_commission" x-model.number="agencyCommission" class="w-32 text-sm border-slate-200 rounded focus:ring-indigo-500 focus:border-indigo-500 text-right font-bold text-slate-700">
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Sales Tax</span>
                                        <div class="flex items-center border border-slate-200 rounded px-2 bg-slate-50 focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                                            <input type="number" name="sales_tax_percentage" x-model.number="salesTaxRate" class="w-16 bg-transparent border-none p-1 text-center font-bold text-slate-700 focus:ring-0" step="0.1">
                                            <span class="text-xs font-bold text-slate-500">%</span>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-indigo-600" x-text="calculateSalesTax().toLocaleString()"></span>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-100 pt-4 mt-4">
                                <div class="flex justify-between text-xs font-bold text-slate-500 mb-2 uppercase">
                                    <span>Expenses Subtotal</span>
                                    <span x-text="calculateExpensesSubtotal().toLocaleString()"></span>
                                </div>
                                <div class="flex justify-between text-xs font-bold text-slate-500 mb-2 uppercase">
                                    <span>Commission + Tax</span>
                                    <span x-text="(agencyCommission + calculateSalesTax()).toLocaleString()"></span>
                                </div>
                            </div>

                            <div class="border-t border-slate-200 pt-4 mt-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Total Bill Balance</span>
                                    <span class="text-3xl font-black text-indigo-700" x-text="'PKR ' + calculateTotal().toLocaleString()"></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm uppercase tracking-widest rounded shadow-lg shadow-indigo-600/30 transition-all mt-6">
                                Save & Finalize Bill
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function billForm() {
            return {
                paymentStatus: '{{ old('status', 'unpaid') }}',
                selectedClientId: '',
                clientNtn: '',
                clientAddress: '',
                location: '',
                containerCount: 1,
                agencyCommission: {{ $settings['agency_commission'] }},
                salesTaxRate: {{ $settings['sales_tax_rate'] }},
                cartageRates: @json($settings['cartages']),
                items: [
                    { particular_name: 'Custom Duty and other Taxes', receipt_type: 'P.D', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: false, short_payment: 0, is_fixed: true },
                    { particular_name: 'Token for GD Filling', receipt_type: '', actual_amount: 500, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'KICT/AICT/QICT/KPT wharfage', receipt_type: 'P.oder', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: false, short_payment: 0, is_fixed: true },
                    { particular_name: 'Demurrage', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Shipping Charges', receipt_type: 'P.oder', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: false, short_payment: 0, is_fixed: true },
                    { particular_name: 'Excise & Taxation Fee', receipt_type: 'P.D', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: false, short_payment: 0, is_fixed: true },
                    { particular_name: 'Stamp Duty', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Weighment Charges', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Other Misc Expences', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Additional / Short Duty', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Addition / Short Excise', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Terminal Short Payment', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                    { particular_name: 'Shipping co Short Payment', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: true, short_payment: 0, is_fixed: true },
                ],

                updateClientInfo() {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    this.clientNtn = option.dataset.ntn || '';
                    this.clientAddress = option.dataset.address || '';
                },

                addItem() {
                    this.items.push({ particular_name: '', receipt_type: '', actual_amount: 0, pay_order_amount: 0, is_paid_by_agent: false, short_payment: 0, is_fixed: false });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                calculateItemShort(index) {
                    const item = this.items[index];
                    
                    // Auto-fill PO amount for specific charges
                    const autoFillPOCharges = ['custom duty and other taxes', 'excise & taxation fee'];
                    if (item.is_fixed && autoFillPOCharges.includes(item.particular_name.toLowerCase())) {
                        item.pay_order_amount = item.actual_amount;
                    }

                    if (item.is_paid_by_agent) {
                        item.short_payment = item.actual_amount;
                    } else {
                        item.short_payment = Math.max(0, item.actual_amount - item.pay_order_amount);
                    }
                },

                calculateCartage() {
                    // Check if Cartage row exists, if not add it
                    let cartageIndex = this.items.findIndex(i => i.particular_name.toLowerCase().includes('cartage'));
                    const rate = this.cartageRates[this.location] || 0;
                    const totalCartage = rate * this.containerCount;

                    if (cartageIndex === -1 && rate > 0) {
                        this.items.push({ particular_name: 'Cartage', receipt_type: '', actual_amount: totalCartage, pay_order_amount: 0, is_paid_by_agent: true, short_payment: totalCartage, is_fixed: true });
                    } else if (cartageIndex !== -1) {
                        this.items[cartageIndex].actual_amount = totalCartage;
                        this.items[cartageIndex].is_paid_by_agent = true;
                        this.calculateItemShort(cartageIndex);
                    }
                },

                calculateSalesTax() {
                    return (this.agencyCommission * this.salesTaxRate) / 100;
                },

                calculateExpensesSubtotal() {
                    return this.items.reduce((sum, item) => sum + (item.short_payment || 0), 0);
                },

                calculateTotal() {
                    return this.calculateExpensesSubtotal() + this.agencyCommission + this.calculateSalesTax();
                }
            }
        }
    </script>
    @endpush
</x-admin-layout>
