<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
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
            'NTN Number',
            'Email Address',
            'Total Bills',
            'Total Revenue Generated (PKR)',
            'Pending Amount (PKR)'
        ];
    }

    public function map($client): array
    {
        return [
            $client->name,
            $client->ntn_no ?? 'N/A',
            $client->email ?? 'N/A',
            $client->total_bills,
            $client->total_revenue,
            $client->pending_amount
        ];
    }
}
