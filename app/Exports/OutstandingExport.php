<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class OutstandingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $clients;

    public function __construct(Collection $clients)
    {
        $this->clients = $clients;
    }

    public function collection()
    {
        return $this->clients;
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Total Billing Amount (PKR)',
            'Total Paid (PKR)',
            'Remaining Balance (PKR)',
            'Last Payment Date'
        ];
    }

    public function map($client): array
    {
        $lastPaymentDate = $client->last_payment_date ? Carbon::parse($client->last_payment_date)->format('d M Y') : 'No Payments';
        
        return [
            $client->name,
            $client->total_billing,
            $client->total_paid,
            $client->remaining_balance,
            $lastPaymentDate
        ];
    }
}
