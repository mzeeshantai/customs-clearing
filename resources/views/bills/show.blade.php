<x-admin-layout>
    <x-slot name="header">
        Bill Details: {{ $bill->bill_no }}
    </x-slot>

    <div class="max-w-5xl mx-auto mb-10">
        <!-- Action Buttons (Hidden on Print) -->
        <div class="flex justify-end space-x-4 mb-4 print:hidden">
            <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded shadow hover:bg-indigo-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z"/></svg>
                Print Bill
            </button>
            <a href="{{ route('bills.index') }}" class="px-6 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-bold uppercase tracking-widest rounded shadow hover:bg-slate-50 transition-colors flex items-center">
                Back to List
            </a>
        </div>

        <!-- Payment Status Banner (Hidden on Print) -->
        <div class="mb-4 print:hidden rounded-md overflow-hidden border {{ $bill->status === 'paid' ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50' }} flex items-center justify-between px-5 py-3">
            <div class="flex items-center space-x-3">
                @if($bill->status === 'paid')
                    <span class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white font-black text-sm">✓</span>
                    <div>
                        <p class="text-xs font-black text-emerald-700 uppercase tracking-wide">Payment Received</p>
                        @if($bill->payment_method)
                            <p class="text-[10px] text-emerald-600">Method: <strong>{{ str_replace('_', ' ', ucfirst($bill->payment_method)) }}</strong></p>
                        @endif
                    </div>
                @else
                    <span class="w-8 h-8 rounded-full bg-rose-500 flex items-center justify-center text-white font-black text-sm">!</span>
                    <div>
                        <p class="text-xs font-black text-rose-700 uppercase tracking-wide">Payment Pending</p>
                        <p class="text-[10px] text-rose-600">Amount Due: <strong>PKR {{ number_format($bill->total_amount) }}</strong></p>
                    </div>
                @endif
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-500 uppercase">Total Bill</p>
                <p class="text-xl font-black {{ $bill->status === 'paid' ? 'text-emerald-700' : 'text-rose-700' }}">PKR {{ number_format($bill->total_amount) }}</p>
            </div>
        </div>


        <!-- Printable Bill Container -->
        <div class="bg-white border border-black p-2 text-black font-sans text-[13px] print:border-0 print:p-0 leading-snug">
            <table class="w-full border-collapse border border-black">
                <!-- Header -->
                <tr>
                    <td colspan="6" class="border border-black p-4">
                        <div class="flex items-center">
                            <!-- Logo Area -->
                            <div class="w-48 flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center border-4 border-green-700 text-white font-black text-3xl italic shadow-inner">
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

    <style>
        @media print {
            body { 
                background: white !important; 
                margin: 0;
                padding: 0;
            }
            .print\:hidden { display: none !important; }
            .print\:border-0 { border: none !important; }
            .print\:p-0 { padding: 0 !important; }
            .print\:bg-transparent { background-color: transparent !important; }
            
            @page {
                size: A4;
                margin: 0.5cm;
            }
            
            nav, header, footer, aside, .sidebar {
                display: none !important;
            }
            
            main {
                padding: 0 !important;
            }
            
            .max-w-5xl {
                max-width: 100% !important;
                margin: 0 !important;
            }
        }
    </style>
</x-admin-layout>
