<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Budget;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function index()
    {
        // Get current month data
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Financial summary
        $totalIncome = $this->getTotalIncome($startOfMonth, $endOfMonth);
        $totalExpenses = $this->getTotalExpenses($startOfMonth, $endOfMonth);
        $netIncome = $totalIncome - $totalExpenses;

        // Invoice statistics
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $overdueInvoices = Invoice::overdue()->count();
        $pendingAmount = Invoice::where('status', '!=', 'paid')->sum('balance_due');

        // Recent transactions
        $recentTransactions = Transaction::with(['creator', 'items.account'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly income vs expenses chart data
        $monthlyData = $this->getMonthlyIncomeExpenseData();

        // Expense by category data
        $expenseByCategory = $this->getExpensesByCategoryData($startOfMonth, $endOfMonth);

        // Cash flow data for the last 12 months
        $cashFlowData = $this->getCashFlowData();

        // Top clients by revenue
        $topClients = $this->getTopClientsByRevenue();

        return view('admin.accounting.index', compact(
            'totalIncome',
            'totalExpenses',
            'netIncome',
            'totalInvoices',
            'paidInvoices',
            'overdueInvoices',
            'pendingAmount',
            'recentTransactions',
            'monthlyData',
            'expenseByCategory',
            'cashFlowData',
            'topClients'
        ));
    }

    private function getTotalIncome($startDate, $endDate)
    {
        return Transaction::where('type', 'income')
            ->where('status', 'posted')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('total_amount');
    }

    private function getTotalExpenses($startDate, $endDate)
    {
        return Transaction::where('type', 'expense')
            ->where('status', 'posted')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('total_amount');
    }

    private function getMonthlyIncomeExpenseData()
    {
        $months = [];
        $income = [];
        $expenses = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $months[] = $date->format('M Y');
            $income[] = $this->getTotalIncome($startOfMonth, $endOfMonth);
            $expenses[] = $this->getTotalExpenses($startOfMonth, $endOfMonth);
        }

        return [
            'months' => $months,
            'income' => $income,
            'expenses' => $expenses
        ];
    }

    private function getExpensesByCategoryData($startDate, $endDate)
    {
        return ExpenseCategory::with(['expenses' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate])
                  ->where('status', 'approved');
        }])
        ->get()
        ->map(function($category) {
            return [
                'name' => $category->name,
                'amount' => $category->expenses->sum('total_amount'),
                'color' => $category->color
            ];
        })
        ->filter(function($item) {
            return $item['amount'] > 0;
        })
        ->values();
    }

    private function getCashFlowData()
    {
        $months = [];
        $cashFlow = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $income = $this->getTotalIncome($startOfMonth, $endOfMonth);
            $expenses = $this->getTotalExpenses($startOfMonth, $endOfMonth);

            $months[] = $date->format('M Y');
            $cashFlow[] = $income - $expenses;
        }

        return [
            'months' => $months,
            'cashFlow' => $cashFlow
        ];
    }

    private function getTopClientsByRevenue()
    {
        return Invoice::selectRaw('client_id, SUM(total_amount) as total_revenue')
            ->with('client')
            ->where('status', 'paid')
            ->groupBy('client_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function($invoice) {
                return [
                    'client_name' => $invoice->client->company_name,
                    'total_revenue' => $invoice->total_revenue
                ];
            });
    }

    public function reports()
    {
        return view('admin.accounting.reports');
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Get income accounts
        $incomeAccounts = Account::where('type', 'income')
            ->with(['transactionItems' => function($query) use ($startDate, $endDate) {
                $query->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->where('status', 'posted')
                      ->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            }])
            ->get();

        // Get expense accounts
        $expenseAccounts = Account::where('type', 'expense')
            ->with(['transactionItems' => function($query) use ($startDate, $endDate) {
                $query->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->where('status', 'posted')
                      ->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            }])
            ->get();

        $totalIncome = $incomeAccounts->sum(function($account) {
            return $account->transactionItems->sum('credit_amount') - $account->transactionItems->sum('debit_amount');
        });

        $totalExpenses = $expenseAccounts->sum(function($account) {
            return $account->transactionItems->sum('debit_amount') - $account->transactionItems->sum('credit_amount');
        });

        $netIncome = $totalIncome - $totalExpenses;

        return view('admin.accounting.profit-loss', compact(
            'incomeAccounts',
            'expenseAccounts',
            'totalIncome',
            'totalExpenses',
            'netIncome',
            'startDate',
            'endDate'
        ));
    }
}
