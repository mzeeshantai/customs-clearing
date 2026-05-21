<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BillingExport;
use App\Exports\PaymentsExport;
use App\Exports\OutstandingExport;
use App\Exports\ClientsExport;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function billing(Request $request)
    {
        $query = Bill::with('client')->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->export === 'excel') {
            return Excel::download(new BillingExport($query->get()), 'billing_report.xlsx');
        }
        if ($request->export === 'pdf') {
            return Excel::download(new BillingExport($query->get()), 'billing_report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        // Summary Totals
        $totalBillsCount = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalReceived = (clone $query)->sum('paid_amount');
        $totalPendingAmount = (clone $query)->where(function($q) {
                $q->where('status', '!=', 'paid')->orWhereNull('status');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));

        // Monthly Stats (Current Month)
        $thisMonthRevenue = Bill::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('total_amount');
        $thisMonthPending = Bill::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->where(function($q) {
                $q->where('status', '!=', 'paid')->orWhereNull('status');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));

        $paidBillsCount = (clone $query)->where('status', 'paid')->count();
        $unpaidBillsCount = $totalBillsCount - $paidBillsCount;

        // Payment method breakdown (only for paid bills in the filtered set)
        $paymentMethodSummary = (clone $query)
            ->reorder()
            ->where('status', 'paid')
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, count(*) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();

        $bills = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('name')->get();

        return view('reports.billing', compact(
            'bills', 'totalBillsCount', 'totalRevenue', 'totalReceived', 'totalPendingAmount',
            'thisMonthRevenue', 'thisMonthPending',
            'paidBillsCount', 'unpaidBillsCount', 'paymentMethodSummary', 'clients'
        ));
    }


    public function outstanding(Request $request)
    {
        $query = Client::query()
            ->select('clients.*')
            ->withSum('bills as total_billing', 'total_amount')
            ->withSum('bills as total_paid', 'paid_amount')
            ->selectRaw('(SELECT COALESCE(SUM(total_amount), 0) - COALESCE(SUM(paid_amount), 0) FROM bills WHERE bills.client_id = clients.id AND status != "paid") as remaining_balance')
            ->addSelect(['last_payment_date' => Payment::select('payment_date')
                ->join('bills', 'payments.bill_id', '=', 'bills.id')
                ->whereColumn('bills.client_id', 'clients.id')
                ->latest('payment_date')
                ->limit(1)
            ])
            ->havingRaw('remaining_balance > 0');

        if ($request->export === 'excel') {
            return Excel::download(new OutstandingExport($query->get()), 'outstanding_report.xlsx');
        }
        if ($request->export === 'pdf') {
            return Excel::download(new OutstandingExport($query->get()), 'outstanding_report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        $paginatedClients = $query->orderByDesc('remaining_balance')
                                  ->paginate(20)
                                  ->withQueryString();

        $clients = Client::orderBy('name')->get();
        
        $totalOutstanding = Bill::where(function($q) {
                $q->where('status', '!=', 'paid')->orWhereNull('status');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));

        $totalClientsWithBalance = (clone $query)->get()->count();

        return view('reports.outstanding', compact('paginatedClients', 'clients', 'totalOutstanding', 'totalClientsWithBalance'));
    }

    public function clients(Request $request)
    {
        $query = Client::query()
            ->select('clients.*')
            ->withCount('bills as total_bills')
            ->withSum('bills as total_revenue', 'total_amount')
            ->withSum('bills as total_paid', 'paid_amount')
            ->selectRaw('(SELECT COALESCE(SUM(total_amount), 0) - COALESCE(SUM(paid_amount), 0) FROM bills WHERE bills.client_id = clients.id AND status != "paid") as pending_amount')
            ->selectRaw('(SELECT COALESCE(SUM(paid_amount), 0) FROM bills WHERE bills.client_id = clients.id AND MONTH(date) = ? AND YEAR(date) = ?) as monthly_collection', [now()->month, now()->year]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('ntn_no', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'revenue_desc') {
                $query->orderByDesc('total_revenue');
            } elseif ($request->sort === 'revenue_asc') {
                $query->orderBy('total_revenue');
            }
        } else {
            $query->orderByDesc('total_revenue');
        }

        if ($request->export === 'excel') {
            return Excel::download(new ClientsExport($query->get()), 'client_summary.xlsx');
        }
        if ($request->export === 'pdf') {
            return Excel::download(new ClientsExport($query->get()), 'client_summary.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        $topClients = (clone $query)->take(5)->get();
        $paginatedClients = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('name')->get();

        // Summary Totals
        $totalRevenue = Bill::sum('total_amount');
        $totalReceived = Bill::sum('paid_amount');
        $totalPending = Bill::where(function($q) {
                $q->where('status', '!=', 'paid')->orWhereNull('status');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));
        
        // Monthly Stats (Current Month)
        $thisMonthRevenue = Bill::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('total_amount');
        $thisMonthReceived = Bill::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('paid_amount');
        $thisMonthPending = Bill::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->where(function($q) {
                $q->where('status', '!=', 'paid')->orWhereNull('status');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));

        $totalClientsCount = Client::count();

        return view('reports.clients', compact(
            'paginatedClients', 'topClients', 'clients', 
            'totalRevenue', 'totalReceived', 'totalPending', 'totalClientsCount',
            'thisMonthRevenue', 'thisMonthReceived', 'thisMonthPending'
        ));
    }

    private function getSalesTaxData(Request $request)
    {
        $query = Bill::with('client')->orderBy('date');

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month);
            $query->whereYear('date', $date->year)->whereMonth('date', $date->month);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            // Default to current month if no filter applied
            if (!$request->has('clear_filters')) {
                $query->whereYear('date', now()->year)->whereMonth('date', now()->month);
            }
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('client', function($q2) use ($request) {
                    $q2->where('ntn_no', 'like', '%'.$request->search.'%')
                      ->orWhere('name', 'like', '%'.$request->search.'%');
                })->orWhere('bill_no', 'like', '%'.$request->search.'%');
            });
        }

        $bills = $query->get();
        $groupedBills = $bills->groupBy('client_id');
        
        $grandTotalInvoices = $bills->count();
        $grandTotalAgency = $bills->sum('agency_commission');
        $grandTotalSalesTax = $bills->sum('sales_tax_amount');
        
        $summary = [];
        foreach ($groupedBills as $clientId => $clientBills) {
            $client = $clientBills->first()->client;
            $summary[] = (object) [
                'client_name' => $client->name,
                'ntn_no' => $client->ntn_no ?? '-',
                'invoice_count' => $clientBills->count(),
                'agency_commission' => $clientBills->sum('agency_commission'),
                'sales_tax' => $clientBills->sum('sales_tax_amount'),
            ];
        }

        return compact('groupedBills', 'summary', 'grandTotalInvoices', 'grandTotalAgency', 'grandTotalSalesTax');
    }

    public function salesTax(Request $request)
    {
        $data = $this->getSalesTaxData($request);
        $clients = Client::orderBy('name')->get();
        
        return view('reports.sales-tax', array_merge($data, ['clients' => $clients]));
    }

    public function printSalesTax(Request $request)
    {
        $data = $this->getSalesTaxData($request);
        return view('reports.sales-tax-print', $data);
    }

    public function expenses(Request $request)
    {
        $query = \App\Models\Expense::with('category')->latest('expense_date');

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month);
            $query->whereYear('expense_date', $date->year)->whereMonth('expense_date', $date->month);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } else {
             if (!$request->has('clear_filters')) {
                $query->whereYear('expense_date', now()->year)->whereMonth('expense_date', now()->month);
            }
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $expenses = $query->get();

        $totalExpenses = $expenses->sum('amount');
        
        $salaryExpenses = $expenses->filter(function($expense) {
            return stripos($expense->category->name ?? '', 'Salary') !== false;
        })->sum('amount');

        $utilityExpenses = $expenses->filter(function($expense) {
            return stripos($expense->category->name ?? '', 'Utility') !== false || stripos($expense->category->name ?? '', 'Bill') !== false;
        })->sum('amount');

        $categoryBreakdown = $expenses->groupBy(function($item) {
            return $item->category ? $item->category->name : 'Uncategorized';
        })->map(function ($group) {
            return $group->sum('amount');
        })->sortDesc();

        $paymentMethodBreakdown = $expenses->groupBy('payment_method')->map(function ($group) {
            return $group->sum('amount');
        });

        $categories = \App\Models\ExpenseCategory::orderBy('name')->get();

        return view('reports.expenses', compact(
            'expenses', 
            'totalExpenses', 
            'salaryExpenses',
            'utilityExpenses',
            'categoryBreakdown', 
            'paymentMethodBreakdown', 
            'categories'
        ));
    }

    public function financial(Request $request)
    {
        $billsQuery = Bill::query();
        $expensesQuery = \App\Models\Expense::query();

        $monthFilter = request('month', now()->format('Y-m'));
        $date = \Carbon\Carbon::parse($monthFilter);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $billsQuery->whereBetween('date', [$request->start_date, $request->end_date]);
            $expensesQuery->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } else {
            // Default to month filter
            $billsQuery->whereYear('date', $date->year)->whereMonth('date', $date->month);
            $expensesQuery->whereYear('expense_date', $date->year)->whereMonth('expense_date', $date->month);
        }

        $totalRevenue = (clone $billsQuery)->sum('total_amount');
        $totalReceived = (clone $billsQuery)->sum('paid_amount');
        
        $totalPending = (clone $billsQuery)->where(function($q) {
            $q->where('status', '!=', 'paid')->orWhereNull('status');
        })->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(total_amount, 0) - COALESCE(paid_amount, 0)'));
        
        $totalSalesTax = (clone $billsQuery)->sum('sales_tax_amount');

        // Sum COALESCE(final_amount, amount) for expenses
        $totalExpenses = (clone $expensesQuery)->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(final_amount, amount)'));

        $deductTax = $request->has('deduct_tax') ? $request->boolean('deduct_tax') : false;

        $netProfit = $totalReceived - $totalExpenses;
        
        if ($deductTax) {
            $netProfit -= $totalSalesTax;
        }

        return view('reports.financial', compact(
            'totalRevenue',
            'totalReceived',
            'totalPending',
            'totalSalesTax',
            'totalExpenses',
            'netProfit',
            'deductTax',
            'monthFilter'
        ));
    }
}
