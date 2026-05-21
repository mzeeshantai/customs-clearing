<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'expense_category_id',
        'employee_id',
        'title',
        'amount',
        'bonus',
        'deduction',
        'salary_month',
        'final_amount',
        'expense_date',
        'payment_method',
        'reference_no',
        'notes',
        'attachment_path',
        'status',
        'user_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'salary_month' => 'date',
        'amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deduction' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id')->withTrashed();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'System']);
    }

    /**
     * Check if this expense is a salary-type expense.
     */
    public function isSalary(): bool
    {
        $categoryName = $this->category?->name ?? '';
        return in_array(strtolower($categoryName), ['salary', 'salaries & wages']);
    }

    /**
     * Get the display amount (final_amount for salary, amount for others).
     */
    public function getDisplayAmountAttribute(): float
    {
        return $this->final_amount ?? $this->amount ?? 0;
    }
}
