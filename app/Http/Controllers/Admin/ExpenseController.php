<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['category', 'project', 'employee', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Expense::count(),
            'draft' => Expense::where('status', 'draft')->count(),
            'submitted' => Expense::where('status', 'submitted')->count(),
            'approved' => Expense::where('status', 'approved')->count(),
            'rejected' => Expense::where('status', 'rejected')->count(),
            'paid' => Expense::where('status', 'paid')->count(),
            'total_amount' => Expense::where('status', 'approved')->sum('total_amount'),
            'pending_amount' => Expense::whereIn('status', ['submitted', 'approved'])->sum('total_amount'),
            'billable_amount' => Expense::where('is_billable', true)->where('status', 'approved')->sum('total_amount'),
        ];

        return view('admin.expenses.index', compact('expenses', 'stats'));
    }

    public function create()
    {
        $categories = ExpenseCategory::active()->get();
        $projects = Project::where('status', 'active')->get();
        $employees = Employee::where('status', 'active')->get();
        
        return view('admin.expenses.create', compact('categories', 'projects', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'employee_id' => 'nullable|exists:employees,id',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,company_card,other',
            'receipt_number' => 'nullable|string|max:255',
            'is_billable' => 'boolean',
            'is_reimbursable' => 'boolean',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('expenses', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $expense = Expense::create([
            'category_id' => $request->category_id,
            'project_id' => $request->project_id,
            'employee_id' => $request->employee_id,
            'expense_date' => $request->expense_date,
            'vendor_name' => $request->vendor_name,
            'description' => $request->description,
            'amount' => $request->amount,
            'tax_amount' => $request->tax_amount ?? 0,
            'payment_method' => $request->payment_method,
            'receipt_number' => $request->receipt_number,
            'attachments' => $attachments,
            'is_billable' => $request->boolean('is_billable'),
            'is_reimbursable' => $request->boolean('is_reimbursable'),
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.expenses.show', $expense)
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'project', 'employee', 'creator', 'approver']);
        
        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if (in_array($expense->status, ['approved', 'paid'])) {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Cannot edit approved or paid expenses.');
        }

        $categories = ExpenseCategory::active()->get();
        $projects = Project::where('status', 'active')->get();
        $employees = Employee::where('status', 'active')->get();
        
        return view('admin.expenses.edit', compact('expense', 'categories', 'projects', 'employees'));
    }

    public function update(Request $request, Expense $expense)
    {
        if (in_array($expense->status, ['approved', 'paid'])) {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Cannot update approved or paid expenses.');
        }

        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'employee_id' => 'nullable|exists:employees,id',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,company_card,other',
            'receipt_number' => 'nullable|string|max:255',
            'is_billable' => 'boolean',
            'is_reimbursable' => 'boolean',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $attachments = $expense->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('expenses', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $expense->update([
            'category_id' => $request->category_id,
            'project_id' => $request->project_id,
            'employee_id' => $request->employee_id,
            'expense_date' => $request->expense_date,
            'vendor_name' => $request->vendor_name,
            'description' => $request->description,
            'amount' => $request->amount,
            'tax_amount' => $request->tax_amount ?? 0,
            'payment_method' => $request->payment_method,
            'receipt_number' => $request->receipt_number,
            'attachments' => $attachments,
            'is_billable' => $request->boolean('is_billable'),
            'is_reimbursable' => $request->boolean('is_reimbursable'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.expenses.show', $expense)
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if (in_array($expense->status, ['approved', 'paid'])) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'Cannot delete approved or paid expenses.');
        }

        // Delete attachments
        if ($expense->attachments) {
            foreach ($expense->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function submit(Expense $expense)
    {
        if ($expense->status === 'draft') {
            $expense->status = 'submitted';
            $expense->save();
            
            return back()->with('success', 'Expense submitted for approval.');
        }

        return back()->with('error', 'Only draft expenses can be submitted.');
    }

    public function approve(Expense $expense)
    {
        if ($expense->status === 'submitted') {
            $expense->approve(Auth::id());
            
            return back()->with('success', 'Expense approved successfully.');
        }

        return back()->with('error', 'Only submitted expenses can be approved.');
    }

    public function reject(Expense $expense)
    {
        if ($expense->status === 'submitted') {
            $expense->reject();
            
            return back()->with('success', 'Expense rejected.');
        }

        return back()->with('error', 'Only submitted expenses can be rejected.');
    }

    public function downloadAttachment(Expense $expense, $index)
    {
        if (!isset($expense->attachments[$index])) {
            abort(404);
        }

        $attachment = $expense->attachments[$index];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment['name']);
    }
}
