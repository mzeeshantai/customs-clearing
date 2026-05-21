

{{-- resources/views/salaries/index.blade.php --}}
<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Salary Records</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Manage employee salary payments</p>
            </div>
            <a href="{{ route('salaries.create') }}" class="btn-brand">
                <i class="bi bi-plus-lg mr-2"></i> Add Salary
            </a>
        </div>

        <x-flash-messages />

        <!-- Filters -->
        <div class="card-c">
            <div class="card-body-c">
                <form action="{{ route('salaries.index') }}" method="GET" class="filter-row">
                    <div class="flex-1">
                        <label class="form-label-c">Search Salaries</label>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#64748b]"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Employee name or month..." class="form-input-c pl-10" />
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn-brand px-6 py-3">Search</button>
                        <a href="{{ route('salaries.index') }}" class="btn-c outline px-6 py-3 ml-2">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Salary List -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Basic Salary</th>
                            <th>Bonus</th>
                            <th>Deduction</th>
                            <th class="text-right">Final Amount</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                            <tr>
                                <td class="font-bold text-[#0f172a]">{{ optional($salary->employee)->name ?? 'N/A' }}</td>
                                <td class="text-sm">{{ $salary->salary_month->format('F Y') }}</td>
                                <td class="text-sm">Rs. {{ number_format($salary->basic_salary, 2) }}</td>
                                <td class="text-sm">{{ $salary->bonus ? 'Rs. '.number_format($salary->bonus, 2) : '-' }}</td>
                                <td class="text-sm">{{ $salary->deduction ? 'Rs. '.number_format($salary->deduction, 2) : '-' }}</td>
                                <td class="text-right font-bold text-[#0f172a]">Rs. {{ number_format($salary->final_salary ?? $salary->final_amount ?? 0, 2) }}</td>
                                <td class="text-center">
                                    @if($salary->status)
                                        <span class="badge-c success"><span class="ind"></span> Paid</span>
                                    @else
                                        <span class="badge-c danger"><span class="ind"></span> Pending</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('salaries.edit', $salary) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-[#64748b] hover:bg-[#f1f5f9] hover:text-[#0f172a] transition-all">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this salary record?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-[#dc2626] hover:bg-[#fee2e2] transition-all">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="w-16 h-16 rounded-2xl bg-[#f8fafc] flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-cash text-2xl text-[#64748b]"></i>
                                    </div>
                                    <h3 class="text-[#0f172a] font-bold mb-1">No Salary Records</h3>
                                    <p class="text-[#64748b] text-sm">Add a salary entry to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($salaries->hasPages())
                <div class="p-4 border-t border-[#e5e9f2]">
                    {{ $salaries->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
?>
