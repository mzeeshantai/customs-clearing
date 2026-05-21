<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'designation',
        'phone',
        'joining_date',
        'monthly_salary',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'monthly_salary' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Get salary expenses linked to this employee.
     */
    public function salaryExpenses()
    {
        return $this->hasMany(Expense::class, 'employee_id');
    }
}
