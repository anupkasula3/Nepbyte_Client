@extends('layouts.admin')

@section('title', 'Accounting Dashboard')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Accounting Dashboard</h1>
        <p class="text-gray-600 mt-2">Financial overview and key metrics</p>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Income -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Income</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalIncome, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalExpenses, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Net Income -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 {{ $netIncome >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg">
                    <i class="fas fa-chart-line {{ $netIncome >= 0 ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Net Income</p>
                    <p class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($netIncome, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pending Amount -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Amount</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($pendingAmount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Statistics</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Invoices</span>
                    <span class="font-semibold">{{ $totalInvoices }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Paid Invoices</span>
                    <span class="font-semibold text-green-600">{{ $paidInvoices }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Overdue Invoices</span>
                    <span class="font-semibold text-red-600">{{ $overdueInvoices }}</span>
                </div>
            </div>
        </div>

        <!-- Top Clients -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Clients by Revenue</h3>
            <div class="space-y-3">
                @foreach($topClients as $client)
                <div class="flex justify-between">
                    <span class="text-gray-600 truncate">{{ $client['client_name'] }}</span>
                    <span class="font-semibold">${{ number_format($client['total_revenue'], 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.invoices.create') }}" 
                   class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Invoice
                </a>
                <a href="{{ route('admin.expenses.create') }}" 
                   class="block w-full bg-green-600 text-white text-center py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Expense
                </a>
                <a href="{{ route('admin.accounting.reports') }}" 
                   class="block w-full bg-purple-600 text-white text-center py-2 px-4 rounded-md hover:bg-purple-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>View Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Income vs Expenses Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Income vs Expenses (Last 12 Months)</h3>
            <canvas id="incomeExpenseChart" height="300"></canvas>
        </div>

        <!-- Expenses by Category -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Expenses by Category (This Month)</h3>
            <canvas id="expenseCategoryChart" height="300"></canvas>
        </div>
    </div>

    <!-- Cash Flow Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cash Flow Trend (Last 12 Months)</h3>
        <canvas id="cashFlowChart" height="200"></canvas>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Transaction
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_number }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($transaction->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->transaction_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type_color }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($transaction->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->status_color }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Income vs Expenses Chart
const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
new Chart(incomeExpenseCtx, {
    type: 'bar',
    data: {
        labels: @json($monthlyData['months']),
        datasets: [{
            label: 'Income',
            data: @json($monthlyData['income']),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgba(34, 197, 94, 1)',
            borderWidth: 1
        }, {
            label: 'Expenses',
            data: @json($monthlyData['expenses']),
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});

// Expenses by Category Chart
const expenseCategoryCtx = document.getElementById('expenseCategoryChart').getContext('2d');
new Chart(expenseCategoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json($expenseByCategory->pluck('name')),
        datasets: [{
            data: @json($expenseByCategory->pluck('amount')),
            backgroundColor: @json($expenseByCategory->pluck('color')),
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': $' + context.parsed.toLocaleString();
                    }
                }
            }
        }
    }
});

// Cash Flow Chart
const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
new Chart(cashFlowCtx, {
    type: 'line',
    data: {
        labels: @json($cashFlowData['months']),
        datasets: [{
            label: 'Cash Flow',
            data: @json($cashFlowData['cashFlow']),
            borderColor: 'rgba(59, 130, 246, 1)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Cash Flow: $' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
