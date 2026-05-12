<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payments;

    public function __construct(Collection $payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'Payment Date',
            'Client Name',
            'Invoice Reference',
            'Received Amount (PKR)',
            'Payment Method',
            'Reference No.'
        ];
    }

    public function map($payment): array
    {
        return [
            Carbon::parse($payment->payment_date)->format('d M Y'),
            $payment->bill->client->name ?? 'Unknown',
            $payment->bill->bill_no ?? 'Unknown',
            $payment->amount,
            $payment->payment_method,
            $payment->reference_no
        ];
    }
}
