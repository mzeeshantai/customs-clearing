<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Tax Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .sub-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }

        th {
            background-color: #e5e7eb;
            font-weight: bold;
            white-space: nowrap;
        }

        .text-left { text-align: left; }
        .text-right { text-align: right; }
        
        .totals-row td {
            font-weight: bold;
            background-color: #e5e7eb;
        }

        .section-spacer {
            height: 15px;
        }

        /* Summary title specific */
        .summary-header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 30px 0 10px 0;
            text-decoration: underline;
        }

        .summary-table th {
            white-space: normal;
        }

        @media print {
            body {
                padding: 0;
            }
            .page-break {
                page-break-before: always;
            }
            button {
                display: none !important;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print Report</button>

    @php
        $monthLabel = request('month') ? \Carbon\Carbon::parse(request('month'))->format('F-Y') : now()->format('F-Y');
    @endphp

    <div class="header-title">M/S: {{ env('APP_NAME', 'SEA PEARL SERVICES') }}</div>
    <div class="sub-title">SALES TAX INVOICES MONTH OF {{ strtoupper($monthLabel) }}. (PARTY-WISE)</div>

    @if(!request('type') || request('type') == 'details')
        @foreach($groupedBills as $clientId => $bills)
            @php
                $client = $bills->first()->client;
                $clientTotalAgency = $bills->sum('agency_commission');
                $clientTotalSalesTax = $bills->sum('sales_tax_amount');
            @endphp
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">S.NO</th>
                        <th style="width: 8%">INV #</th>
                        <th style="width: 10%">DATE</th>
                        <th style="width: 12%">NTN #</th>
                        <th style="width: 20%">OWNER NAME</th>
                        <th style="width: 25%">NAME OF PARTY</th>
                        <th style="width: 10%">AGENCY CGHS</th>
                        <th style="width: 10%">S. TAX 15%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bills as $index => $bill)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bill->bill_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($bill->date)->format('d/m/Y') }}</td>
                            <td>{{ $client->ntn_no ?? '-' }}</td>
                            <td>{{ $client->owner_name ?? '-' }}</td>
                            <td>{{ $client->name }}</td>
                            <td class="text-right">{{ number_format($bill->agency_commission) }}</td>
                            <td class="text-right">{{ number_format($bill->sales_tax_amount) }}</td>
                        </tr>
                    @endforeach
                    <tr class="totals-row">
                        <td colspan="6" class="text-right">TOTAL</td>
                        <td class="text-right">{{ number_format($clientTotalAgency) }}</td>
                        <td class="text-right">{{ number_format($clientTotalSalesTax) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="section-spacer"></div>
        @endforeach
        
        @if(count($groupedBills) > 0)
        <table>
            <tbody>
                <tr class="totals-row" style="font-size: 13px;">
                    <td colspan="6" class="text-right" style="width: 80%">GRAND TOTAL (DETAILS)</td>
                    <td class="text-right" style="width: 10%">{{ number_format($grandTotalAgency) }}</td>
                    <td class="text-right" style="width: 10%">{{ number_format($grandTotalSalesTax) }}</td>
                </tr>
            </tbody>
        </table>
        @endif
    @endif

    @if(!request('type') || request('type') == 'summary')
        @if(count($summary) > 0)
            @if(!request('type'))
            <div class="page-break"></div>
            @endif
            <div class="summary-header">
                M/S: {{ env('APP_NAME', 'SEA PEARL SERVICES') }}.<br>
                FOR THE MONTH OF {{ strtoupper($monthLabel) }} (ONLY CLEARING ACCOUNT)<br>
                AS ON {{ now()->format('d-m-Y') }}
            </div>
            
            <table class="summary-table">
                <thead>
                    <tr>
                        <th style="width: 5%">S.NO</th>
                        <th style="width: 35%">IMPORTER NAME</th>
                        <th style="width: 15%">SNTN#</th>
                        <th style="width: 10%">NO OF<br>INVOICES</th>
                        <th style="width: 17%">SALES TAX<br>VALUE</th>
                        <th style="width: 18%">AMOUNT OF<br>SALES TAX 15%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $row->client_name }}</td>
                            <td>{{ $row->ntn_no }}</td>
                            <td>{{ $row->invoice_count }}</td>
                            <td class="text-right">{{ number_format($row->agency_commission, 2) }}</td>
                            <td class="text-right">{{ number_format($row->sales_tax, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="totals-row">
                        <td colspan="3"></td>
                        <td>{{ number_format($grandTotalInvoices) }}</td>
                        <td class="text-right">{{ number_format($grandTotalAgency, 2) }}</td>
                        <td class="text-right">{{ number_format($grandTotalSalesTax, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
    @endif
</body>
</html>
