<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('employee')->latest('salary_month');

        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $query->whereYear('salary_month', $date->year)->whereMonth('salary_month', $date->month);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $salaries = $query->paginate(20)->withQueryString();
        $employees = Employee::orderBy('name')->get();

        return view('salaries.index', compact('salaries', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', true)->orderBy('name')->get();
        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_month' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string|max:255',
            'status' => 'required|in:paid,pending',
            'notes' => 'nullable|string',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deduction'] = $validated['deduction'] ?? 0;
        $validated['final_salary'] = $validated['basic_salary'] + $validated['bonus'] - $validated['deduction'];

        $salary = Salary::create($validated);

        // Auto-create associated Expense record
        $this->syncExpense($salary);

        return redirect()->route('salaries.index')->with('success', 'Salary record created successfully.');
    }

    public function show(Salary $salary)
    {
        return view('salaries.show', compact('salary'));
    }

    public function edit(Salary $salary)
    {
        $employees = Employee::where('status', true)->orderBy('name')->get();
        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_month' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string|max:255',
            'status' => 'required|in:paid,pending',
            'notes' => 'nullable|string',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deduction'] = $validated['deduction'] ?? 0;
        $validated['final_salary'] = $validated['basic_salary'] + $validated['bonus'] - $validated['deduction'];

        $salary->update($validated);

        // Auto-update associated Expense record
        $this->syncExpense($salary);

        return redirect()->route('salaries.index')->with('success', 'Salary record updated successfully.');
    }

    public function destroy(Salary $salary)
    {
        if ($salary->expense_id) {
            Expense::where('id', $salary->expense_id)->delete();
        }
        $salary->delete();
        
        return redirect()->route('salaries.index')->with('success', 'Salary record deleted successfully.');
    }

    private function syncExpense(Salary $salary)
    {
        $category = ExpenseCategory::firstOrCreate(
            ['name' => 'Salaries & Wages'],
            ['status' => true]
        );

        $monthName = Carbon::parse($salary->salary_month)->format('F Y');
        $expenseData = [
            'expense_category_id' => $category->id,
            'title' => 'Salary: ' . $salary->employee->name . ' (' . $monthName . ')',
            'amount' => $salary->final_salary,
            'expense_date' => $salary->payment_date,
            'payment_method' => $salary->payment_method,
            'reference_no' => $salary->reference_no,
            'status' => $salary->status,
            'notes' => 'Auto-generated from Payroll/Salaries module. ' . $salary->notes,
            'user_id' => auth()->id() ?? 1,
        ];

        if ($salary->expense_id) {
            Expense::where('id', $salary->expense_id)->update($expenseData);
        } else {
            $expense = Expense::create($expenseData);
            $salary->update(['expense_id' => $expense->id]);
        }
    }
}
