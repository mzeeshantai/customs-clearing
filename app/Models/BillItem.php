<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'particular_name',
        'receipt_type',
        'receipt_date',
        'actual_amount',
        'pay_order_amount',
        'is_paid_by_agent',
        'short_payment',
        'billable_amount'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
