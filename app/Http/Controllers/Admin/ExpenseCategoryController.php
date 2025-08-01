<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')
            ->orderBy('name')
            ->paginate(15);

        $stats = [
            'total' => ExpenseCategory::count(),
            'active' => ExpenseCategory::where('is_active', true)->count(),
            'inactive' => ExpenseCategory::where('is_active', false)->count(),
            'total_expenses' => ExpenseCategory::withSum('expenses', 'total_amount')->get()->sum('expenses_sum_total_amount'),
        ];

        return view('admin.expense-categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.expense-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $category = ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
        ]);

        return redirect()->route('admin.expense-categories.show', $category)
            ->with('success', 'Expense category created successfully.');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->loadCount('expenses');

        // Get recent expenses for this category
        $recentExpenses = $expenseCategory->expenses()
            ->with(['employee', 'project'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get monthly expense totals for the last 12 months
        $monthlyExpenses = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyExpenses[] = [
                'month' => $date->format('M Y'),
                'total' => $expenseCategory->expenses()
                    ->whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year)
                    ->where('status', 'approved')
                    ->sum('total_amount')
            ];
        }

        return view('admin.expense-categories.show', compact(
            'expenseCategory',
            'recentExpenses',
            'monthlyExpenses'
        ));
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('admin.expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
        ]);

        return redirect()->route('admin.expense-categories.show', $expenseCategory)
            ->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Check if category has expenses
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('admin.expense-categories.index')
                ->with('error', 'Cannot delete category with existing expenses.');
        }

        $expenseCategory->delete();

        return redirect()->route('admin.expense-categories.index')
            ->with('success', 'Expense category deleted successfully.');
    }

    public function toggleStatus(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->is_active = !$expenseCategory->is_active;
        $expenseCategory->save();

        $status = $expenseCategory->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category {$status} successfully.");
    }

    public function analytics()
    {
        $categories = ExpenseCategory::withSum('expenses', 'total_amount')
            ->orderBy('expenses_sum_total_amount', 'desc')
            ->get();

        // Get monthly breakdown for all categories
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthData = [
                'month' => $date->format('M Y'),
                'categories' => []
            ];

            foreach ($categories as $category) {
                $monthData['categories'][$category->name] = $category->expenses()
                    ->whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year)
                    ->where('status', 'approved')
                    ->sum('total_amount');
            }

            $monthlyData[] = $monthData;
        }

        return view('admin.expense-categories.analytics', compact('categories', 'monthlyData'));
    }
}
