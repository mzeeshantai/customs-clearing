<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Employee Registry</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Manage staff and payroll members</p>
            </div>
            <a href="{{ route('employees.create') }}" class="btn-brand">
                <i class="bi bi-plus-lg mr-2"></i> Add Employee
            </a>
        </div>

        <x-flash-messages />

        <!-- Filters & Search -->
        <div class="card-c">
            <div class="card-body-c">
                <form action="{{ route('employees.index') }}" method="GET" class="filter-row">
                    <div class="flex-1">
                        <label class="form-label-c">Search Employees</label>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#64748b]"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or designation..." class="form-input-c pl-10">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn-brand px-6 py-3">Search</button>
                        <a href="{{ route('employees.index') }}" class="btn-c outline px-6 py-3 ml-2">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Employees List -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Contact</th>
                            <th>Joining Date</th>
                            <th class="text-right">Monthly Salary</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div class="font-bold text-[#0f172a]">{{ $employee->name }}</div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#f1f5f9] text-[#475569]">
                                        {{ $employee->designation }}
                                    </span>
                                </td>
                                <td class="text-sm">{{ $employee->phone ?? '-' }}</td>
                                <td class="text-sm">{{ $employee->joining_date->format('M d, Y') }}</td>
                                <td class="text-right font-bold text-[#0f172a]">
                                    Rs. {{ number_format($employee->monthly_salary, 2) }}
                                </td>
                                <td class="text-center">
                                    @if($employee->status)
                                        <span class="badge-c success"><span class="ind"></span> Active</span>
                                    @else
                                        <span class="badge-c danger"><span class="ind"></span> Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('employees.edit', $employee) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-[#64748b] hover:bg-[#f1f5f9] hover:text-[#0f172a] transition-all">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-[#dc2626] hover:bg-[#fee2e2] transition-all">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="w-16 h-16 rounded-2xl bg-[#f8fafc] flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-people text-2xl text-[#64748b]"></i>
                                    </div>
                                    <h3 class="text-[#0f172a] font-bold mb-1">No Employees Found</h3>
                                    <p class="text-[#64748b] text-sm">Get started by adding a new employee to the system.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($employees->hasPages())
                <div class="p-4 border-t border-[#e5e9f2]">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
