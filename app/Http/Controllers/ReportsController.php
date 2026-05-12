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
}
