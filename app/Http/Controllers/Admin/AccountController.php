<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('parent')
            ->orderBy('code')
            ->paginate(20);

        $stats = [
            'total' => Account::count(),
            'active' => Account::where('is_active', true)->count(),
            'assets' => Account::where('type', 'asset')->count(),
            'liabilities' => Account::where('type', 'liability')->count(),
            'equity' => Account::where('type', 'equity')->count(),
            'income' => Account::where('type', 'income')->count(),
            'expenses' => Account::where('type', 'expense')->count(),
        ];

        return view('admin.accounts.index', compact('accounts', 'stats'));
    }

    public function create()
    {
        $parentAccounts = Account::where('is_active', true)->orderBy('code')->get();

        return view('admin.accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:accounts,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'subtype' => 'required|in:current_asset,fixed_asset,current_liability,long_term_liability,owner_equity,operating_income,other_income,operating_expense,other_expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'opening_balance' => 'nullable|numeric',
        ]);

        $account = Account::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'subtype' => $request->subtype,
            'parent_id' => $request->parent_id,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
        ]);

        return redirect()->route('admin.accounts.show', $account)
            ->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load(['parent', 'children', 'transactionItems.transaction']);

        // Get recent transactions for this account
        $recentTransactions = $account->transactionItems()
            ->with('transaction')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.accounts.show', compact('account', 'recentTransactions'));
    }

    public function edit(Account $account)
    {
        $parentAccounts = Account::where('is_active', true)
            ->where('id', '!=', $account->id)
            ->orderBy('code')
            ->get();

        return view('admin.accounts.edit', compact('account', 'parentAccounts'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'subtype' => 'required|in:current_asset,fixed_asset,current_liability,long_term_liability,owner_equity,operating_income,other_income,operating_expense,other_expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'opening_balance' => 'nullable|numeric',
        ]);

        // Prevent setting parent to self or child
        if ($request->parent_id == $account->id) {
            return back()->withInput()->withErrors([
                'parent_id' => 'An account cannot be its own parent.'
            ]);
        }

        $account->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'subtype' => $request->subtype,
            'parent_id' => $request->parent_id,
            'opening_balance' => $request->opening_balance ?? 0,
        ]);

        return redirect()->route('admin.accounts.show', $account)
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        // Check if account has transactions
        if ($account->transactionItems()->count() > 0) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Cannot delete account with existing transactions.');
        }

        // Check if account has children
        if ($account->children()->count() > 0) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Cannot delete account with child accounts.');
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account deleted successfully.');
    }

    public function toggleStatus(Account $account)
    {
        // Check if account has active transactions before deactivating
        if ($account->is_active && $account->transactionItems()->count() > 0) {
            return back()->with('error', 'Cannot deactivate account with existing transactions.');
        }

        $account->is_active = !$account->is_active;
        $account->save();

        $status = $account->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Account {$status} successfully.");
    }

    public function balanceSheet()
    {
        $assets = Account::where('type', 'asset')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $liabilities = Account::where('type', 'liability')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $equity = Account::where('type', 'equity')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $totalAssets = $assets->sum('current_balance');
        $totalLiabilities = $liabilities->sum('current_balance');
        $totalEquity = $equity->sum('current_balance');

        return view('admin.accounts.balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity'
        ));
    }

    public function trialBalance()
    {
        $accounts = Account::where('is_active', true)
            ->orderBy('code')
            ->get();

        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($accounts as $account) {
            $debitBalance = $account->transactionItems()->sum('debit_amount');
            $creditBalance = $account->transactionItems()->sum('credit_amount');

            if (in_array($account->type, ['asset', 'expense'])) {
                $account->trial_balance = $account->opening_balance + $debitBalance - $creditBalance;
                if ($account->trial_balance > 0) {
                    $totalDebits += $account->trial_balance;
                } else {
                    $totalCredits += abs($account->trial_balance);
                }
            } else {
                $account->trial_balance = $account->opening_balance + $creditBalance - $debitBalance;
                if ($account->trial_balance > 0) {
                    $totalCredits += $account->trial_balance;
                } else {
                    $totalDebits += abs($account->trial_balance);
                }
            }
        }

        return view('admin.accounts.trial-balance', compact(
            'accounts',
            'totalDebits',
            'totalCredits'
        ));
    }
}
