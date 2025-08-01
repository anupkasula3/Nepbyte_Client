<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::overdue()->count(),
            'total_amount' => Invoice::sum('total_amount'),
            'paid_amount' => Invoice::sum('paid_amount'),
            'pending_amount' => Invoice::where('status', '!=', 'paid')->sum('balance_due'),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $projects = Project::with('client')->get();
        
        return view('admin.invoices.create', compact('clients', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.task_id' => 'nullable|exists:tasks,id',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'client_id' => $request->client_id,
                'project_id' => $request->project_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'tax_rate' => $request->tax_rate ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'notes' => $request->notes,
                'terms' => $request->terms,
                'created_by' => Auth::id(),
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'balance_due' => 0,
            ]);

            foreach ($request->items as $itemData) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'task_id' => $itemData['task_id'] ?? null,
                ]);
            }

            $invoice->calculateTotals();

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items.task', 'payments', 'creator']);
        
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Cannot edit paid invoices.');
        }

        $clients = Client::where('status', 'active')->get();
        $projects = Project::with('client')->get();
        $invoice->load(['items.task']);
        
        return view('admin.invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Cannot update paid invoices.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.task_id' => 'nullable|exists:tasks,id',
        ]);

        DB::beginTransaction();
        try {
            $invoice->update([
                'client_id' => $request->client_id,
                'project_id' => $request->project_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'tax_rate' => $request->tax_rate ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ]);

            // Delete existing items and create new ones
            $invoice->items()->delete();

            foreach ($request->items as $itemData) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'task_id' => $itemData['task_id'] ?? null,
                ]);
            }

            $invoice->calculateTotals();

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Cannot delete paid invoices.');
        }

        if ($invoice->payments()->count() > 0) {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Cannot delete invoices with payments.');
        }

        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function markAsSent(Invoice $invoice)
    {
        if ($invoice->status === 'draft') {
            $invoice->status = 'sent';
            $invoice->save();
            
            return back()->with('success', 'Invoice marked as sent.');
        }

        return back()->with('error', 'Only draft invoices can be marked as sent.');
    }

    public function getProjectTasks(Project $project)
    {
        $tasks = $project->tasks()
            ->where('status', 'completed')
            ->with('assignedEmployee')
            ->get();

        return response()->json($tasks);
    }
}
