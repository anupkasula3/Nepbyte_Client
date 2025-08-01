<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['creator', 'items.account'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Transaction::count(),
            'draft' => Transaction::where('status', 'draft')->count(),
            'posted' => Transaction::where('status', 'posted')->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
            'total_amount' => Transaction::where('status', 'posted')->sum('total_amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();

        return view('admin.transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:income,expense,transfer,adjustment',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.description' => 'nullable|string',
            'items.*.debit_amount' => 'nullable|numeric|min:0',
            'items.*.credit_amount' => 'nullable|numeric|min:0',
        ]);

        // Validate that each item has either debit or credit (but not both)
        foreach ($request->items as $index => $item) {
            $hasDebit = !empty($item['debit_amount']) && $item['debit_amount'] > 0;
            $hasCredit = !empty($item['credit_amount']) && $item['credit_amount'] > 0;
            if (!$hasDebit && !$hasCredit) {
                return back()->withInput()->withErrors([
                    "items.{$index}.debit_amount" => 'Each item must have either a debit or credit amount.'
                ]);
            }
            if ($hasDebit && $hasCredit) {
                return back()->withInput()->withErrors([
                    "items.{$index}.debit_amount" => 'Each item cannot have both debit and credit amounts.'
                ]);
            }
        }

        // Calculate total debits and credits
        $totalDebits = collect($request->items)->sum('debit_amount');
        $totalCredits = collect($request->items)->sum('credit_amount');

        if (abs($totalDebits - $totalCredits) > 0.01) {
            return back()->withInput()->withErrors([
                'items' => 'Total debits must equal total credits.'
            ]);
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'transaction_date' => $request->transaction_date,
                'reference' => $request->reference,
                'description' => $request->description,
                'total_amount' => $totalDebits, // or $totalCredits, they should be equal
                'type' => $request->type,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'account_id' => $itemData['account_id'],
                    'description' => $itemData['description'],
                    'debit_amount' => $itemData['debit_amount'] ?? 0,
                    'credit_amount' => $itemData['credit_amount'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.transactions.show', $transaction)
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error creating transaction: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['creator', 'items.account']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->status === 'posted') {
            return redirect()->route('admin.transactions.show', $transaction)
                ->with('error', 'Cannot edit posted transactions.');
        }

        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        $transaction->load('items');
        return view('admin.transactions.edit', compact('transaction', 'accounts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->status === 'posted') {
            return redirect()->route('admin.transactions.show', $transaction)
                ->with('error', 'Cannot update posted transactions.');
        }

        $request->validate([
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:income,expense,transfer,adjustment',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.description' => 'nullable|string',
            'items.*.debit_amount' => 'nullable|numeric|min:0',
            'items.*.credit_amount' => 'nullable|numeric|min:0',
        ]);

        // Validate that each item has either debit or credit (but not both)
        foreach ($request->items as $index => $item) {
            $hasDebit = !empty($item['debit_amount']) && $item['debit_amount'] > 0;
            $hasCredit = !empty($item['credit_amount']) && $item['credit_amount'] > 0;
            if (!$hasDebit && !$hasCredit) {
                return back()->withInput()->withErrors([
                    "items.{$index}.debit_amount" => 'Each item must have either a debit or credit amount.'
                ]);
            }
            if ($hasDebit && $hasCredit) {
                return back()->withInput()->withErrors([
                    "items.{$index}.debit_amount" => 'Each item cannot have both debit and credit amounts.'
                ]);
            }
        }

        // Calculate total debits and credits
        $totalDebits = collect($request->items)->sum('debit_amount');
        $totalCredits = collect($request->items)->sum('credit_amount');

        if (abs($totalDebits - $totalCredits) > 0.01) {
            return back()->withInput()->withErrors([
                'items' => 'Total debits must equal total credits.'
            ]);
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'transaction_date' => $request->transaction_date,
                'reference' => $request->reference,
                'description' => $request->description,
                'total_amount' => $totalDebits,
                'type' => $request->type,
                'notes' => $request->notes,
            ]);

            // Delete existing items and create new ones
            $transaction->items()->delete();

            foreach ($request->items as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'account_id' => $itemData['account_id'],
                    'description' => $itemData['description'],
                    'debit_amount' => $itemData['debit_amount'] ?? 0,
                    'credit_amount' => $itemData['credit_amount'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.transactions.show', $transaction)
                ->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error updating transaction: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->status === 'posted') {
            return redirect()->route('admin.transactions.index')
                ->with('error', 'Cannot delete posted transactions.');
        }

        $transaction->delete();

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    public function post(Transaction $transaction)
    {
        if ($transaction->status !== 'draft') {
            return back()->with('error', 'Only draft transactions can be posted.');
        }

        try {
            $transaction->post();
            return back()->with('success', 'Transaction posted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error posting transaction: ' . $e->getMessage());
        }
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->status === 'posted') {
            return back()->with('error', 'Cannot cancel posted transactions.');
        }

        $transaction->status = 'cancelled';
        $transaction->save();

        return back()->with('success', 'Transaction cancelled successfully.');
    }
}
