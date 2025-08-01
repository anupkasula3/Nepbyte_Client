@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Invoice</h1>
            <p class="text-gray-600 mt-2">Generate a new invoice for a client</p>
        </div>
        <a href="{{ route('admin.invoices.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Invoices
        </a>
    </div>

    <form method="POST" action="{{ route('admin.invoices.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                    <select name="client_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->company_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project (Optional)</label>
                    <select name="project_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }} ({{ $project->client->company_name }})
                        </option>
                        @endforeach
                    </select>
                    @error('project_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('invoice_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('due_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tax Rate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ old('tax_rate', 0) }}" min="0" max="100" step="0.01"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tax_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Amount ($)</label>
                    <input type="number" name="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="0.01"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('discount_amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                <textarea name="terms" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('terms', 'Payment is due within 30 days of invoice date. Late payments may incur additional fees.') }}</textarea>
                @error('terms')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Items</h3>
            
            <div id="invoice-items">
                <div class="invoice-item grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 border border-gray-200 rounded-md">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <input type="text" name="items[0][description]" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Service description">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="items[0][quantity]" value="1" min="0.01" step="0.01" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 item-quantity">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 item-price">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                        <input type="text" readonly class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50 item-total">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-item bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 transition-colors" style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Item
            </button>

            <!-- Totals -->
            <div class="mt-6 bg-gray-50 p-4 rounded-md">
                <div class="flex justify-between items-center text-lg font-semibold">
                    <span>Total Amount:</span>
                    <span id="grand-total">$0.00</span>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.invoices.index') }}" 
               class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Create Invoice
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 1;

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('invoice-items');
    const newItem = document.querySelector('.invoice-item').cloneNode(true);
    
    // Update input names and clear values
    newItem.querySelectorAll('input').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', `[${itemIndex}]`));
        }
        if (!input.hasAttribute('readonly')) {
            input.value = input.getAttribute('name').includes('quantity') ? '1' : '';
        } else {
            input.value = '';
        }
    });
    
    // Show remove button
    newItem.querySelector('.remove-item').style.display = 'block';
    
    container.appendChild(newItem);
    itemIndex++;
    
    // Add event listeners to new item
    addItemEventListeners(newItem);
});

// Remove item functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-item')) {
        e.target.closest('.invoice-item').remove();
        calculateGrandTotal();
    }
});

// Add event listeners to calculate totals
function addItemEventListeners(item) {
    const quantityInput = item.querySelector('.item-quantity');
    const priceInput = item.querySelector('.item-price');
    const totalInput = item.querySelector('.item-total');
    
    function calculateItemTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        totalInput.value = '$' + total.toFixed(2);
        calculateGrandTotal();
    }
    
    quantityInput.addEventListener('input', calculateItemTotal);
    priceInput.addEventListener('input', calculateItemTotal);
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.item-total').forEach(input => {
        const value = input.value.replace('$', '');
        total += parseFloat(value) || 0;
    });
    
    const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
    const discount = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
    
    const taxAmount = total * (taxRate / 100);
    const grandTotal = total + taxAmount - discount;
    
    document.getElementById('grand-total').textContent = '$' + grandTotal.toFixed(2);
}

// Initialize event listeners for the first item
addItemEventListeners(document.querySelector('.invoice-item'));

// Recalculate when tax rate or discount changes
document.querySelector('input[name="tax_rate"]').addEventListener('input', calculateGrandTotal);
document.querySelector('input[name="discount_amount"]').addEventListener('input', calculateGrandTotal);
</script>
@endpush
@endsection
