<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $totalClients = \App\Models\Client::count();
    $totalBills = \App\Models\Bill::count();
    $pendingBills = \App\Models\Bill::whereDate('created_at', today())->count(); 
    
    // Calculate Total Recovery (Actual Paid Amount)
    $totalRecovery = \App\Models\Bill::sum('paid_amount');
    
    // Calculate Total Pending Amount (Remaining Balance on Unpaid/Partially Paid Bills)
    $totalPendingAmount = \App\Models\Bill::where(function($q) {
            $q->where('status', '!=', 'paid')->orWhereNull('status');
        })
        ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));
    
    $todayBills = \App\Models\Bill::with('client')->whereDate('created_at', today())->latest()->get();
    $latestClients = \App\Models\Client::latest()->take(5)->get();

    return view('dashboard', compact(
        'totalClients', 
        'totalBills', 
        'pendingBills', 
        'totalRecovery', 
        'totalPendingAmount',
        'todayBills', 
        'latestClients'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------------------
    | Accountant Routes
    | Accessible by: admin, accountant
    |----------------------------------------------------------------------
    */
    Route::middleware(['accountant'])->group(function () {
        Route::resource('clients', \App\Http\Controllers\ClientController::class);
        Route::resource('bills', \App\Http\Controllers\BillController::class);
        Route::patch('/bills/{bill}/status', [\App\Http\Controllers\BillController::class, 'updateStatus'])->name('bills.update-status');
        
        // Expense Management
        Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class)->except(['show']);
        Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
        
        // HR & Payroll
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

        // AJAX endpoint for fetching employee details (used by expense form)
        Route::get('ajax/employee/{employee}', function (\App\Models\Employee $employee) {
            return response()->json([
                'id' => $employee->id,
                'name' => $employee->name,
                'designation' => $employee->designation,
                'monthly_salary' => $employee->monthly_salary,
            ]);
        })->name('ajax.employee.show');
    });

    Route::middleware(['admin'])->group(function () {
        // Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::resource('cartages', \App\Http\Controllers\CartageController::class)->only(['store', 'update', 'destroy']);

        // Reports - Shared (Admin + Accountant)
        Route::middleware(['accountant'])->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportsController::class, 'index'])->name('index');
            Route::get('/billing', [\App\Http\Controllers\ReportsController::class, 'billing'])->name('billing');
            Route::get('/sales-tax', [\App\Http\Controllers\ReportsController::class, 'salesTax'])->name('sales-tax');
            Route::get('/sales-tax/print', [\App\Http\Controllers\ReportsController::class, 'printSalesTax'])->name('sales-tax.print');
            Route::get('/financial', [\App\Http\Controllers\ReportsController::class, 'financial'])->name('financial');
            Route::get('/expenses', [\App\Http\Controllers\ReportsController::class, 'expenses'])->name('expenses');
        });

        // Reports - Admin Only
        Route::middleware(['admin'])->prefix('reports')->name('reports.')->group(function () {
            Route::get('/outstanding', [\App\Http\Controllers\ReportsController::class, 'outstanding'])->name('outstanding');
            Route::get('/clients', [\App\Http\Controllers\ReportsController::class, 'clients'])->name('clients');
        });
    });
});

require __DIR__.'/auth.php';
