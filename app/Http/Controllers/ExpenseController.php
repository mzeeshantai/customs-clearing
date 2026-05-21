<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Category names that trigger salary workflow.
     */
    private array $salaryCategories = ['salary', 'salaries & wages'];

    public function index(Request $request)
    {
        $query = Expense::with(['category', 'user', 'employee'])->latest('expense_date');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('reference_no', 'like', '%'.$request->search.'%');
            });
        }

        $expenses = $query->paginate(20)->withQueryString();
        $categories = ExpenseCategory::where('status', true)->orderBy('name')->get();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::where('status', true)->orderBy('name')->get();
        $employees = Employee::where('status', true)->orderBy('name')->get();
        return view('expenses.create', compact('categories', 'employees'));
    }

    public function store(Request $request)
    {
        $isSalary = $this->isSalaryCategory($request->category_name);

        // Base validation
        $rules = [
            'category_name' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            'status' => 'required|in:paid,pending',
        ];

        if ($isSalary) {
            $rules['employee_id'] = 'required|exists:employees,id';
            $rules['salary_month'] = 'required|date';
            $rules['bonus'] = 'nullable|numeric|min:0';
            $rules['deduction'] = 'nullable|numeric|min:0';
        } else {
            $rules['title'] = 'required|string|max:255';
            $rules['amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('expenses', 'public');
            $validated['attachment_path'] = $path;
        }

        // Resolve category
        $category = ExpenseCategory::firstOrCreate(
            ['name' => $request->category_name],
            ['status' => true]
        );
        $validated['expense_category_id'] = $category->id;
        unset($validated['category_name']);

        $validated['user_id'] = auth()->id();

        // Salary-specific logic
        if ($isSalary) {
            $employee = Employee::findOrFail($validated['employee_id']);
            $bonus = $validated['bonus'] ?? 0;
            $deduction = $validated['deduction'] ?? 0;
            $baseSalary = $employee->monthly_salary;

            $validated['title'] = 'Salary: ' . $employee->name . ' (' . \Carbon\Carbon::parse($validated['salary_month'])->format('F Y') . ')';
            $validated['amount'] = $baseSalary;
            $validated['bonus'] = $bonus;
            $validated['deduction'] = $deduction;
            $validated['final_amount'] = $baseSalary + $bonus - $deduction;
        }

        // Remove attachment key (not a DB column)
        unset($validated['attachment']);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('status', true)->orderBy('name')->get();
        $employees = Employee::where('status', true)->orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories', 'employees'));
    }

    public function update(Request $request, Expense $expense)
    {
        $isSalary = $this->isSalaryCategory($request->category_name);

        // Base validation
        $rules = [
            'category_name' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            'status' => 'required|in:paid,pending',
        ];

        if ($isSalary) {
            $rules['employee_id'] = 'required|exists:employees,id';
            $rules['salary_month'] = 'required|date';
            $rules['bonus'] = 'nullable|numeric|min:0';
            $rules['deduction'] = 'nullable|numeric|min:0';
        } else {
            $rules['title'] = 'required|string|max:255';
            $rules['amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            if ($expense->attachment_path) {
                Storage::disk('public')->delete($expense->attachment_path);
            }
            $path = $request->file('attachment')->store('expenses', 'public');
            $validated['attachment_path'] = $path;
        } elseif ($request->has('remove_attachment') && $request->remove_attachment == '1') {
             if ($expense->attachment_path) {
                Storage::disk('public')->delete($expense->attachment_path);
            }
            $validated['attachment_path'] = null;
        }

        // Resolve category
        $category = ExpenseCategory::firstOrCreate(
            ['name' => $request->category_name],
            ['status' => true]
        );
        $validated['expense_category_id'] = $category->id;
        unset($validated['category_name']);

        // Salary-specific logic
        if ($isSalary) {
            $employee = Employee::findOrFail($validated['employee_id']);
            $bonus = $validated['bonus'] ?? 0;
            $deduction = $validated['deduction'] ?? 0;
            $baseSalary = $employee->monthly_salary;

            $validated['title'] = 'Salary: ' . $employee->name . ' (' . \Carbon\Carbon::parse($validated['salary_month'])->format('F Y') . ')';
            $validated['amount'] = $baseSalary;
            $validated['bonus'] = $bonus;
            $validated['deduction'] = $deduction;
            $validated['final_amount'] = $baseSalary + $bonus - $deduction;
        } else {
            // Clear salary fields for non-salary expenses
            $validated['employee_id'] = null;
            $validated['bonus'] = null;
            $validated['deduction'] = null;
            $validated['salary_month'] = null;
            $validated['final_amount'] = null;
        }

        // Remove attachment key (not a DB column)
        unset($validated['attachment']);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Check if the given category name triggers salary workflow.
     */
    private function isSalaryCategory(?string $categoryName): bool
    {
        if (!$categoryName) return false;
        return in_array(strtolower(trim($categoryName)), $this->salaryCategories);
    }
}
