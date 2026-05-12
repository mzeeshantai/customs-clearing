<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BillingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bills;

    public function __construct(Collection $bills)
    {
        $this->bills = $bills;
    }

    public function collection()
    {
        return $this->bills;
    }

    public function headings(): array
    {
        return [
            'Reference No',
            'Client Name',
            'Bill Date',
            'Bill Amount (PKR)',
            'Paid Amount (PKR)',
            'Balance (PKR)',
            'Status'
        ];
    }

    public function map($bill): array
    {
        return [
            $bill->bill_no,
            $bill->client->name,
            Carbon::parse($bill->date)->format('d M Y'),
            $bill->total_amount,
            $bill->paid_amount,
            $bill->balance,
            $bill->status
        ];
    }
}
