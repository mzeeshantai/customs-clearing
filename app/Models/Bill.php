<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_no',
        'date',
        'client_id',
        'gd_no',
        'gd_date',
        'location',
        'container_count',
        'agency_commission',
        'sales_tax_percentage',
        'sales_tax_amount',
        'total_amount',
        'paid_amount',
        'status',
        'payment_method',
        'marks_and_nos',
        'remarks'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getBalanceAttribute()
    {
        if ($this->status === 'paid') {
            return 0;
        }
        return max(0, $this->total_amount - $this->paid_amount);
    }
}
