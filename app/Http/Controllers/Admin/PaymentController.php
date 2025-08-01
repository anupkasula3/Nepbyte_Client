<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['invoice', 'client', 'receivedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function create(Invoice $invoice = null)
    {
        $clients = Client::where('status', 'active')->get();
        $invoices = Invoice::where('status', '!=', 'paid')
            ->where('balance_due', '>', 0)
            ->with('client')
            ->get();

        return view('admin.payments.create', compact('clients', 'invoices', 'invoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,paypal,other',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Validate amount doesn't exceed invoice balance if invoice is specified
        if ($request->invoice_id) {
            $invoice = Invoice::find($request->invoice_id);
            if ($request->amount > $invoice->balance_due) {
                return back()->withInput()->withErrors([
                    'amount' => 'Payment amount cannot exceed the invoice balance due.'
                ]);
            }
        }

        $payment = Payment::create([
            'client_id' => $request->client_id,
            'invoice_id' => $request->invoice_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'status' => 'completed',
            'received_by' => Auth::id(),
        ]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice', 'client', 'receivedBy']);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        if ($payment->status === 'completed') {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Cannot edit completed payments.');
        }

        $clients = Client::where('status', 'active')->get();
        $invoices = Invoice::where('status', '!=', 'paid')
            ->where('balance_due', '>', 0)
            ->with('client')
            ->get();

        return view('admin.payments.edit', compact('payment', 'clients', 'invoices'));
    }

    public function update(Request $request, Payment $payment)
    {
        if ($payment->status === 'completed') {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Cannot update completed payments.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,paypal,other',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        // Validate amount doesn't exceed invoice balance if invoice is specified
        if ($request->invoice_id) {
            $invoice = Invoice::find($request->invoice_id);
            $currentPayments = Payment::where('invoice_id', $invoice->id)
                ->where('id', '!=', $payment->id)
                ->where('status', 'completed')
                ->sum('amount');
            if (($currentPayments + $request->amount) > $invoice->total_amount) {
                return back()->withInput()->withErrors([
                    'amount' => 'Total payments cannot exceed the invoice amount.'
                ]);
            }
        }

        $payment->update([
            'client_id' => $request->client_id,
            'invoice_id' => $request->invoice_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->status === 'completed') {
            return redirect()->route('admin.payments.index')
                ->with('error', 'Cannot delete completed payments.');
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function getInvoiceDetails(Invoice $invoice)
    {
        return response()->json([
            'client_id' => $invoice->client_id,
            'balance_due' => $invoice->balance_due,
            'total_amount' => $invoice->total_amount,
            'paid_amount' => $invoice->paid_amount,
        ]);
    }
}
