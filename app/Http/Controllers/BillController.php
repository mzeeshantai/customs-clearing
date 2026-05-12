<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with('client')->latest();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . trim($request->search) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('bill_no', 'like', $searchTerm)
                  ->orWhere('gd_no', 'like', $searchTerm)
                  ->orWhereHas('client', function($cq) use ($searchTerm) {
                      $cq->where('name', 'like', $searchTerm);
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bills = $query->paginate(10)->withQueryString();
        
        $clients = Client::orderBy('name')->get();
        $years = range(date('Y'), 2020); // From current year down to 2020

        return view('bills.index', compact('bills', 'clients', 'years'));
    }

    public function create()
    {
        $clients = Client::all();
        $settings = [
            'cartage_kict' => Setting::get('cartage_kict', 23000),
            'cartage_port_qasim' => Setting::get('cartage_port_qasim', 30000),
            'sales_tax_rate' => Setting::get('sales_tax_rate_default', 15),
            'agency_commission' => Setting::get('agency_commission_default', 8500),
        ];

        $lastBill = Bill::orderBy('id', 'desc')->first();
        $nextBillNo = 'SP-1001';

        if ($lastBill) {
            $lastBillNo = $lastBill->bill_no;
            if (preg_match('/^(.*?)([0-9]+)$/', $lastBillNo, $matches)) {
                $prefix = $matches[1];
                $number = (int)$matches[2];
                $nextBillNo = $prefix . ($number + 1);
            }
        }

        return view('bills.create', compact('clients', 'settings', 'nextBillNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_no' => 'required|unique:bills,bill_no',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'gd_no' => 'required|string',
            'gd_date' => 'required|date',
            'location' => 'nullable|string',
            'container_count' => 'required|integer|min:1',
            'agency_commission' => 'required|numeric',
            'sales_tax_percentage' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
            'payment_method' => 'required_if:status,paid|nullable|in:cash,bank_transfer,pay_order,cheque,online_transfer,other',
            'marks_and_nos' => 'nullable|string',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.particular_name' => 'required|string',
            'items.*.actual_amount' => 'required|numeric',
            'items.*.pay_order_amount' => 'nullable|numeric',
            'items.*.is_paid_by_agent' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $bill = Bill::create([
                'bill_no' => $validated['bill_no'],
                'date' => $validated['date'],
                'client_id' => $validated['client_id'],
                'gd_no' => $validated['gd_no'],
                'gd_date' => $validated['gd_date'],
                'location' => $validated['location'] ?? null,
                'container_count' => $validated['container_count'],
                'agency_commission' => $validated['agency_commission'],
                'sales_tax_percentage' => $validated['sales_tax_percentage'],
                'status' => $validated['status'],
                'payment_method' => $validated['status'] === 'paid' ? ($validated['payment_method'] ?? null) : null,
                'marks_and_nos' => $validated['marks_and_nos'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $totalBillable = 0;

            foreach ($validated['items'] as $item) {
                $actual = $item['actual_amount'];
                $poAmount = $item['pay_order_amount'] ?? 0;
                $paidByAgent = $item['is_paid_by_agent'] ?? false;
                
                $billable = 0;
                $shortPayment = 0;

                if ($paidByAgent) {
                    $billable = $actual;
                } else {
                    $shortPayment = max(0, $actual - $poAmount);
                    $billable = $shortPayment;
                }

                $bill->items()->create([
                    'particular_name' => $item['particular_name'],
                    'receipt_type' => $item['receipt_type'] ?? null,
                    'actual_amount' => $actual,
                    'pay_order_amount' => $poAmount,
                    'is_paid_by_agent' => $paidByAgent,
                    'short_payment' => $shortPayment,
                    'billable_amount' => $billable,
                ]);

                $totalBillable += $billable;
            }

            // Calculate Sales Tax on Commission
            $salesTaxAmount = ($bill->agency_commission * $bill->sales_tax_percentage) / 100;
            
            // Add Commission and Sales Tax to total
            $finalTotal = $totalBillable + $bill->agency_commission + $salesTaxAmount;

            $bill->update([
                'sales_tax_amount' => $salesTaxAmount,
                'total_amount' => $finalTotal
            ]);

            return redirect()->route('bills.index')->with('success', 'Bill generated successfully.');
        });
    }

    public function show(Bill $bill)
    {
        $bill->load(['client', 'items']);
        return view('bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can edit bills.');
        }

        $bill->load('items');
        $clients = Client::orderBy('name')->get();
        $settings = [
            'cartage_kict' => Setting::get('cartage_kict', 23000),
            'cartage_port_qasim' => Setting::get('cartage_port_qasim', 30000),
            'sales_tax_rate' => Setting::get('sales_tax_rate_default', 15),
            'agency_commission' => Setting::get('agency_commission_default', 8500),
        ];

        return view('bills.edit', compact('bill', 'clients', 'settings'));
    }

    public function update(Request $request, Bill $bill)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can update bills.');
        }

        $validated = $request->validate([
            'bill_no' => 'required|unique:bills,bill_no,' . $bill->id,
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'gd_no' => 'required|string',
            'gd_date' => 'required|date',
            'location' => 'nullable|string',
            'container_count' => 'required|integer|min:1',
            'agency_commission' => 'required|numeric',
            'sales_tax_percentage' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
            'payment_method' => 'required_if:status,paid|nullable|in:cash,bank_transfer,pay_order,cheque,online_transfer,other',
            'marks_and_nos' => 'nullable|string',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:bill_items,id',
            'items.*.particular_name' => 'required|string',
            'items.*.actual_amount' => 'required|numeric',
            'items.*.pay_order_amount' => 'nullable|numeric',
            'items.*.is_paid_by_agent' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($validated, $request, $bill) {
            $bill->update([
                'bill_no' => $validated['bill_no'],
                'date' => $validated['date'],
                'client_id' => $validated['client_id'],
                'gd_no' => $validated['gd_no'],
                'gd_date' => $validated['gd_date'],
                'location' => $validated['location'] ?? null,
                'container_count' => $validated['container_count'],
                'agency_commission' => $validated['agency_commission'],
                'sales_tax_percentage' => $validated['sales_tax_percentage'],
                'status' => $validated['status'],
                'payment_method' => $validated['status'] === 'paid' ? ($validated['payment_method'] ?? null) : null,
                'marks_and_nos' => $validated['marks_and_nos'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Soft-delete removed items
            $submittedItemIds = collect($validated['items'])->pluck('id')->filter()->toArray();
            $bill->items()->whereNotIn('id', $submittedItemIds)->delete();

            $totalBillable = 0;

            foreach ($validated['items'] as $item) {
                $actual = $item['actual_amount'];
                $poAmount = $item['pay_order_amount'] ?? 0;
                $paidByAgent = $item['is_paid_by_agent'] ?? false;
                
                $billable = 0;
                $shortPayment = 0;

                if ($paidByAgent) {
                    $billable = $actual;
                } else {
                    $shortPayment = max(0, $actual - $poAmount);
                    $billable = $shortPayment;
                }

                $itemData = [
                    'particular_name' => $item['particular_name'],
                    'receipt_type' => $item['receipt_type'] ?? null,
                    'actual_amount' => $actual,
                    'pay_order_amount' => $poAmount,
                    'is_paid_by_agent' => $paidByAgent,
                    'short_payment' => $shortPayment,
                    'billable_amount' => $billable,
                ];

                if (isset($item['id'])) {
                    $bill->items()->where('id', $item['id'])->update($itemData);
                } else {
                    $bill->items()->create($itemData);
                }

                $totalBillable += $billable;
            }

            // Calculate Sales Tax on Commission
            $salesTaxAmount = ($bill->agency_commission * $bill->sales_tax_percentage) / 100;
            
            // Add Commission and Sales Tax to total
            $finalTotal = $totalBillable + $bill->agency_commission + $salesTaxAmount;

            $bill->update([
                'sales_tax_amount' => $salesTaxAmount,
                'total_amount' => $finalTotal
            ]);

            return redirect()->route('bills.index')->with('success', 'Bill updated successfully.');
        });
    }

    public function destroy(Bill $bill)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete bills.');
        }

        $bill->delete();
        return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,unpaid',
            'payment_method' => 'required_if:status,paid|nullable|in:cash,bank_transfer,pay_order,cheque,online_transfer,other',
        ]);

        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === 'paid') {
            $updateData['paid_amount'] = $bill->total_amount;
            $updateData['payment_method'] = $validated['payment_method'] ?? null;
        } else {
            $updateData['paid_amount'] = 0;
            $updateData['payment_method'] = null;
        }

        $bill->update($updateData);

        return back()->with('success', 'Bill status updated to ' . ucfirst($validated['status']) . '.');
    }
}
