@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg mt-8">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Create Salary Record</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 rounded">
            <ul class="list-disc list-inside text-red-700 dark:text-red-200">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('salaries.store') }}" class="space-y-6">
        @csrf
        <div>
            <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
            <select name="employee_id" id="employee_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="" disabled selected>Select an employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="salary_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salary Month</label>
                <input type="date" name="salary_month" id="salary_month" value="{{ old('salary_month') }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Basic Salary</label>
                <input type="number" step="0.01" name="basic_salary" id="basic_salary" value="{{ old('basic_salary') }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus (optional)</label>
                <input type="number" step="0.01" name="bonus" id="bonus" value="{{ old('bonus') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="deduction" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deduction (optional)</label>
                <input type="number" step="0.01" name="deduction" id="deduction" value="{{ old('deduction') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                <input type="text" name="payment_method" id="payment_method" value="{{ old('payment_method') }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
        </div>

        <div>
            <label for="reference_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference No (optional)</label>
            <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select name="status" id="status" required class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (optional)</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('salaries.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">Create Salary</button>
        </div>
    </form>
</div>
@endsection
