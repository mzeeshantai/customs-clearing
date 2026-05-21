<x-admin-layout>
    <div x-data="{ 
        showModal: false, 
        isEdit: false, 
        categoryId: null,
        categoryName: '',
        categoryStatus: true,
        
        openAddModal() {
            this.isEdit = false;
            this.categoryId = null;
            this.categoryName = '';
            this.categoryStatus = true;
            this.showModal = true;
        },
        
        openEditModal(id, name, status) {
            this.isEdit = true;
            this.categoryId = id;
            this.categoryName = name;
            this.categoryStatus = status == 1;
            this.showModal = true;
        }
    }" class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Expense Categories</h1>
                <p class="text-[#64748b] text-[14px] mt-1">Manage categories for operational expenses</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="openAddModal()" class="btn-brand py-2 text-[13px]">
                    <i class="bi bi-plus-lg me-1"></i> Add Category
                </button>
            </div>
        </div>

        <x-flash-messages />

        <!-- Table -->
        <div class="card-c overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-c">
                    <thead>
                        <tr>
                            <th class="w-16 text-center">#</th>
                            <th>Category Name</th>
                            <th>Usage Count</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                            <tr>
                                <td class="text-center text-[#64748b]">{{ $index + 1 }}</td>
                                <td class="font-bold text-[#0f172a]">{{ $category->name }}</td>
                                <td>
                                    <span class="badge-c bg-[#f1f5f9] text-[#475569] border-[#e2e8f0]">
                                        {{ $category->expenses()->count() }} Expenses
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($category->status)
                                        <span class="badge-c success py-1 px-3 text-[10px]">Active</span>
                                    @else
                                        <span class="badge-c bg-gray-100 text-gray-600 border-gray-200 py-1 px-3 text-[10px]">Disabled</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button @click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->status ? 1 : 0 }})" 
                                            class="w-8 h-8 rounded-lg grid place-items-center text-[#64748b] hover:bg-[#f8fafc] transition-all" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('expense-categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg grid place-items-center text-[#dc2626] hover:bg-[#fee2e2] transition-all" title="Delete" onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-[#64748b] bg-[#f8fafc]/50">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-[#f1f5f9] rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-tags text-3xl text-[#cbd5e1]"></i>
                                        </div>
                                        <div class="font-bold">No categories found</div>
                                        <div class="text-sm mt-1">Start by adding your first expense category</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
                     @click="showModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <form :action="isEdit ? '{{ url('expense-categories') }}/' + categoryId : '{{ route('expense-categories.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-[#f8fafc]">
                            <h3 class="text-lg font-bold text-[#0f172a]" x-text="isEdit ? 'Edit Category' : 'Add New Category'"></h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-4">
                            <div class="form-group-c">
                                <label class="form-label-c">Category Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="categoryName" class="form-input-c" required placeholder="e.g., Office Supplies">
                            </div>

                            <div class="form-group-c flex items-center gap-2 mt-4">
                                <input type="checkbox" name="status" id="status" x-model="categoryStatus" value="1" class="rounded border-gray-300 text-[#1565c0] focus:ring-[#1565c0]">
                                <label for="status" class="text-sm font-medium text-[#334155] cursor-pointer">Active (Enabled)</label>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2 border-t border-gray-100">
                            <button type="button" @click="showModal = false" class="btn-c outline py-2 px-4">Cancel</button>
                            <button type="submit" class="btn-brand py-2 px-6">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
