<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 print:hidden">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1.5">
                    <a href="{{ route('bills.index') }}" class="hover:text-[#1565c0]">Bills</a>
                    <i class="bi bi-chevron-right text-[8px]"></i>
                    <span class="text-[#0f172a]">Bill Details</span>
                </div>
                <h1 class="text-2xl font-bold text-[#0f172a]">Invoice #{{ $bill->bill_no }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('bills.index') }}" class="btn-soft py-2 text-[13px]">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <button onclick="window.print()" class="btn-brand py-2 text-[13px]">
                    <i class="bi bi-printer me-1"></i> Print / PDF
                </button>
            </div>
        </div>

        <!-- Status & Summary (Reference style) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 print:hidden">
            <div class="lg:col-span-8">
                <div class="card-c p-6 flex flex-col md:flex-row items-center justify-between gap-6 border-l-4 {{ $bill->status === 'paid' ? 'border-l-[#16a34a]' : 'border-l-[#dc2626]' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl {{ $bill->status === 'paid' ? 'bg-[#f0fdf4] text-[#16a34a]' : 'bg-[#fef2f2] text-[#dc2626]' }} grid place-items-center text-2xl shadow-sm">
                            <i class="bi bi-{{ $bill->status === 'paid' ? 'check-circle-fill' : 'exclamation-circle-fill' }}"></i>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest">Payment Status</div>
                            <div class="text-xl font-bold text-[#0f172a]">{{ $bill->status === 'paid' ? 'Invoice Fully Settled' : 'Payment Outstanding' }}</div>
                            @if($bill->status === 'paid')
                                <div class="text-[12px] text-[#64748b] mt-0.5">Settled via {{ str_replace('_', ' ', ucfirst($bill->payment_method)) }}</div>
                            @else
                                <div class="text-[12px] text-[#64748b] mt-0.5">PKR {{ number_format($bill->total_amount) }} is currently overdue</div>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-1">Total Billable Amount</div>
                        <div class="text-3xl font-black {{ $bill->status === 'paid' ? 'text-[#16a34a]' : 'text-[#dc2626]' }}">
                            <span class="text-sm me-1 opacity-60">PKR</span>{{ number_format($bill->total_amount) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="card-c p-6 h-full flex flex-col justify-center">
                    <div class="text-[11px] font-bold text-[#64748b] uppercase tracking-widest mb-3">Client Account</div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#f1f5f9] grid place-items-center font-bold text-[#1565c0]">
                            {{ substr($bill->client->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-[#0f172a]">{{ $bill->client->name }}</div>
                            <div class="text-[11px] text-[#64748b] font-medium">{{ $bill->client->ntn_no ?: 'No NTN Provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Printable Bill Container -->
        <div class="max-w-5xl mx-auto">
            <div class="bg-white border border-black p-2 text-black font-sans text-[13px] print:border-0 print:p-0 leading-snug shadow-xl print:shadow-none">
                <table class="w-full border-collapse border border-black">
                    <!-- Header -->
                    <tr>
                        <td colspan="6" class="border border-black p-4">
                            <div class="flex items-center">
                                <!-- Logo Area -->
                                <div class="w-48 flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center border-4 border-emerald-700 text-white font-black text-3xl italic">
                                        SPS
                                    </div>
                                    <div class="text-[11px] font-bold mt-2 text-center">C.H.A.L NO. 2016</div>
                                </div>
                                <!-- Title Area -->
                                <div class="flex-1 text-center -ml-10">
                                    <h1 class="text-4xl font-black italic underline tracking-wider mb-1" style="font-family: serif;">{{ App\Models\Setting::get('agency_name', 'SEA PEARL SERVICES') }}</h1>
                                    <p class="text-sm font-bold uppercase">Customs Clearing & Forwarding Agent</p>
                                    <p class="text-xs mt-1 uppercase">{{ App\Models\Setting::get('agency_address', 'OFFICE # 509, 5TH FLOOR, BUSINESS PLAZA, MUMTAZ HASSAN ROAD, KARACHI') }}</p>
                                    <p class="text-xs uppercase">PHONE : {{ App\Models\Setting::get('agency_phone', '32466274') }}</p>
                                    <p class="text-xs uppercase">EMAIL : {{ App\Models\Setting::get('agency_email', 'info@seapearl.com') }}</p>
                                    <p class="text-xs uppercase">NTN NO : {{ App\Models\Setting::get('agency_ntn', '0964557-8') }}</p>
                                    <p class="text-xs uppercase">LICENSE NO : {{ App\Models\Setting::get('agency_license', 'CC-1029-KHI') }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Bill No & Date -->
                    <tr>
                        <td class="border border-black px-2 py-1 font-bold w-[12%] uppercase">BILL NO. S.P</td>
                        <td class="border border-black px-2 py-1 text-center underline w-[15%] font-bold">{{ str_replace('SP-', '', $bill->bill_no) }}</td>
                        <td class="border border-black px-2 py-1 w-[40%]"></td>
                        <td colspan="2" class="border border-black px-2 py-1 font-bold text-right w-[18%] uppercase">DATE:</td>
                        <td class="border border-black px-2 py-1 text-center w-[15%] font-bold">{{ date('d/m/Y', strtotime($bill->date)) }}</td>
                    </tr>

                    <!-- Client Info -->
                    <tr>
                        <td class="border border-black px-2 py-1 font-bold uppercase">Messers</td>
                        <td colspan="5" class="border border-black px-2 py-1 text-center font-bold underline uppercase text-sm tracking-wide">{{ $bill->client->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-2 py-1 font-bold uppercase">Adress</td>
                        <td colspan="5" class="border border-black px-2 py-1 text-center capitalize">{{ $bill->client->address }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black px-2 py-1 font-bold uppercase">Importer / Exporter Sales Tax Reg No. / Ntn No :</td>
                        <td colspan="3" class="border border-black px-2 py-1 text-center font-bold">{{ $bill->client->ntn_no ?: 'N/A' }}</td>
                    </tr>

                    <!-- Table Headers -->
                    <tr class="bg-gray-50 print:bg-transparent">
                        <th colspan="2" class="border border-black px-2 py-1 font-bold text-center uppercase">REFERENCE</th>
                        <th class="border border-black px-2 py-1 font-bold text-center uppercase">PARTICULARS OF CHARGES</th>
                        <th class="border border-black px-2 py-1 font-bold text-center uppercase">RECEIPT</th>
                        <th class="border border-black px-2 py-1 font-bold text-center uppercase">DATED</th>
                        <th class="border border-black px-2 py-1 font-bold text-center uppercase">AMOUNT</th>
                    </tr>

                    <!-- Dynamic Rows -->
                    @php
                        $items = $bill->items->toArray();
                        
                        $items[] = [
                            'particular_name' => 'Agency Comission',
                            'receipt_type' => '',
                            'actual_amount' => $bill->agency_commission
                        ];
                        $items[] = [
                            'particular_name' => '@'.$bill->sales_tax_percentage.'% sales tax/vat on Agency Commision',
                            'receipt_type' => '',
                            'actual_amount' => $bill->sales_tax_amount
                        ];

                        $totalRows = max(count($items), 19); 
                        
                        $leftSide = [
                            ['GD NO:', $bill->gd_no],
                            ['Dated', date('d/m/Y', strtotime($bill->gd_date))],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['MARKS & NOS', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['REMARKS', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                            ['', ''],
                        ];
                    @endphp

                    @for($i = 0; $i < $totalRows; $i++)
                        <tr>
                            @if($i == 9)
                                <td colspan="2" class="border border-black px-2 py-1 font-bold text-center border-b-transparent uppercase">MARKS & NOS</td>
                            @elseif($i == 10)
                                <td colspan="2" class="border border-black px-2 py-1 text-center border-t-transparent font-medium">{{ $bill->marks_and_nos }}</td>
                            @elseif($i == 14)
                                <td colspan="2" class="border border-black px-2 py-1 font-bold text-center border-b-transparent uppercase">REMARKS</td>
                            @elseif($i == 15)
                                <td colspan="2" class="border border-black px-2 py-1 text-center border-t-transparent font-medium">{{ $bill->remarks }}</td>
                            @else
                                <td class="border border-black px-2 py-1">{{ $leftSide[$i][0] ?? '' }}</td>
                                <td class="border border-black px-2 py-1 text-center">{{ $leftSide[$i][1] ?? '' }}</td>
                            @endif
                            
                            <td class="border border-black px-2 py-1">{{ $items[$i]['particular_name'] ?? '' }}</td>
                            <td class="border border-black px-2 py-1">{{ $items[$i]['receipt_type'] ?? '' }}</td>
                            <td class="border border-black px-2 py-1"></td>
                            <td class="border border-black px-2 py-1 text-right">
                                @if(isset($items[$i]))
                                    {{ (isset($items[$i]['actual_amount']) && is_numeric($items[$i]['actual_amount']) && $items[$i]['actual_amount'] > 0) ? number_format($items[$i]['actual_amount']) : '-' }}
                                @endif
                            </td>
                        </tr>
                    @endfor

                    <!-- Totals -->
                    <tr>
                        <td colspan="2" class="border-l border-r border-black px-2 py-1"></td>
                        <td colspan="3" class="border border-black px-2 py-1 text-right">Total of Expenses</td>
                        <td class="border border-black px-2 py-1 text-right font-bold">{{ number_format($bill->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border-l border-r border-black px-2 py-1"></td>
                        <td colspan="3" class="border border-black px-2 py-1 text-right">Less : Advance (if any)</td>
                        <td class="border border-black px-2 py-1 text-right font-bold">-</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border-l border-b border-r border-black px-2 py-1"></td>
                        <td colspan="3" class="border border-black px-2 py-1 text-right">Balance</td>
                        <td class="border border-black px-2 py-1 text-right font-bold">{{ number_format($bill->total_amount) }}</td>
                    </tr>
                </table>

                <!-- Footer Signatures -->
                <div class="px-8 pt-12 pb-4 flex justify-between items-end bg-white">
                    <div></div>
                    <div class="text-right">
                        <p class="text-2xl font-bold italic" style="font-family: serif;">For: {{ App\Models\Setting::get('agency_name', 'SEA PEARL SERVICES') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            aside, nav, header, footer, .sidebar { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            main { padding: 0 !important; max-width: 100% !important; }
            .max-w-5xl { max-width: 100% !important; margin: 0 !important; }
            @page { size: A4; margin: 0.5cm; }
        }
    </style>
</x-admin-layout>
