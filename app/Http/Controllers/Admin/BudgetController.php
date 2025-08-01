<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Account;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Budget::count(),
            'active' => Budget::where('status', 'active')->count(),
            'draft' => Budget::where('status', 'draft')->count(),
            'completed' => Budget::where('status', 'completed')->count(),
            'total_budget' => Budget::where('status', 'active')->sum('total_budget'),
            'total_actual' => Budget::where('status', 'active')->sum('total_actual'),
        ];

        return view('admin.budgets.index', compact('budgets', 'stats'));
    }

    public function create()
    {
        $accounts = Account::where('type', 'expense')->where('is_active', true)->get();
        $expenseCategories = ExpenseCategory::where('is_active', true)->get();

        return view('admin.budgets.create', compact('accounts', 'expenseCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.budgeted_amount' => 'required|numeric|min:0',
            'items.*.account_id' => 'nullable|exists:accounts,id',
            'items.*.expense_category_id' => 'nullable|exists:expense_categories,id',
            'items.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $totalBudget = collect($request->items)->sum('budgeted_amount');

            $budget = Budget::create([
                'name' => $request->name,
                'description' => $request->description,
                'period_type' => $request->period_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_budget' => $totalBudget,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $itemData) {
                BudgetItem::create([
                    'budget_id' => $budget->id,
                    'account_id' => $itemData['account_id'] ?? null,
                    'expense_category_id' => $itemData['expense_category_id'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'description' => $itemData['description'] ?? null,
                    'budgeted_amount' => $itemData['budgeted_amount'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error creating budget: ' . $e->getMessage());
        }
    }

    public function show(Budget $budget)
    {
        $budget->load(['creator', 'items.account', 'items.expenseCategory']);

        // Calculate progress for each item
        foreach ($budget->items as $item) {
            // Here you would calculate actual amounts based on expenses/transactions
            // For now, we'll use the stored actual_amount
            $item->progress_percentage = $item->budgeted_amount > 0
                ? min(($item->actual_amount / $item->budgeted_amount) * 100, 100)
                : 0;
        }

        return view('admin.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        if ($budget->status === 'completed') {
            return redirect()->route('admin.budgets.show', $budget)
                ->with('error', 'Cannot edit completed budgets.');
        }

        $accounts = Account::where('type', 'expense')->where('is_active', true)->get();
        $expenseCategories = ExpenseCategory::where('is_active', true)->get();
        $budget->load('items');

        return view('admin.budgets.edit', compact('budget', 'accounts', 'expenseCategories'));
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->status === 'completed') {
            return redirect()->route('admin.budgets.show', $budget)
                ->with('error', 'Cannot update completed budgets.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.budgeted_amount' => 'required|numeric|min:0',
            'items.*.account_id' => 'nullable|exists:accounts,id',
            'items.*.expense_category_id' => 'nullable|exists:expense_categories,id',
            'items.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $totalBudget = collect($request->items)->sum('budgeted_amount');

            $budget->update([
                'name' => $request->name,
                'description' => $request->description,
                'period_type' => $request->period_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_budget' => $totalBudget,
            ]);

            // Delete existing items and create new ones
            $budget->items()->delete();

            foreach ($request->items as $itemData) {
                BudgetItem::create([
                    'budget_id' => $budget->id,
                    'account_id' => $itemData['account_id'] ?? null,
                    'expense_category_id' => $itemData['expense_category_id'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'description' => $itemData['description'] ?? null,
                    'budgeted_amount' => $itemData['budgeted_amount'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error updating budget: ' . $e->getMessage());
        }
    }

    public function destroy(Budget $budget)
    {
        if ($budget->status === 'active') {
            return redirect()->route('admin.budgets.index')
                ->with('error', 'Cannot delete active budgets.');
        }

        $budget->delete();

        return redirect()->route('admin.budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }

    public function activate(Budget $budget)
    {
        if ($budget->status === 'draft') {
            $budget->status = 'active';
            $budget->save();

            return back()->with('success', 'Budget activated successfully.');
        }

        return back()->with('error', 'Only draft budgets can be activated.');
    }

    public function complete(Budget $budget)
    {
        if ($budget->status === 'active') {
            $budget->status = 'completed';
            $budget->save();

            return back()->with('success', 'Budget marked as completed.');
        }

        return back()->with('error', 'Only active budgets can be completed.');
    }
}
