<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectTrackingController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\AccountingController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view("welcome");
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('equipment', EquipmentController::class);

    // Project Tracking Routes
    Route::prefix('projects/{project}/tracking')->name('projects.tracking.')->group(function () {
        Route::get('dashboard', [ProjectTrackingController::class, 'dashboard'])->name('dashboard');
        Route::get('team', [ProjectTrackingController::class, 'manageTeam'])->name('team');
        Route::post('team', [ProjectTrackingController::class, 'addTeamMember'])->name('team.add');
        Route::put('team/{teamMember}', [ProjectTrackingController::class, 'updateTeamMember'])->name('team.update');
        Route::delete('team/{teamMember}', [ProjectTrackingController::class, 'removeTeamMember'])->name('team.remove');
        Route::get('timeline', [ProjectTrackingController::class, 'timeline'])->name('timeline');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Accounting Routes
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/', [AccountingController::class, 'index'])->name('index');
        Route::get('reports', [AccountingController::class, 'reports'])->name('reports');
        Route::get('profit-loss', [AccountingController::class, 'profitLoss'])->name('profit-loss');
    });

    // Invoice Routes
    Route::resource('invoices', InvoiceController::class);
    Route::patch('invoices/{invoice}/mark-sent', [InvoiceController::class, 'markAsSent'])->name('invoices.mark-sent');
    Route::get('projects/{project}/tasks', [InvoiceController::class, 'getProjectTasks'])->name('projects.tasks');

    // Expense Routes
    Route::resource('expenses', ExpenseController::class);
    Route::patch('expenses/{expense}/submit', [ExpenseController::class, 'submit'])->name('expenses.submit');
    Route::patch('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::patch('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::get('expenses/{expense}/attachments/{index}', [ExpenseController::class, 'downloadAttachment'])->name('expenses.download-attachment');

    // Payment Routes
    Route::resource('payments', PaymentController::class);
    Route::get('payments/create/{invoice?}', [PaymentController::class, 'create'])->name('payments.create-for-invoice');

    // Budget Routes
    Route::resource('budgets', BudgetController::class);
    Route::patch('budgets/{budget}/activate', [BudgetController::class, 'activate'])->name('budgets.activate');
    Route::patch('budgets/{budget}/complete', [BudgetController::class, 'complete'])->name('budgets.complete');

    // Account Routes (Chart of Accounts)
    Route::resource('accounts', AccountController::class);
    Route::patch('accounts/{account}/toggle-status', [AccountController::class, 'toggleStatus'])->name('accounts.toggle-status');

    // Transaction Routes
    Route::resource('transactions', TransactionController::class);
    Route::patch('transactions/{transaction}/post', [TransactionController::class, 'post'])->name('transactions.post');
    Route::patch('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    // Expense Category Routes
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::patch('expense-categories/{expenseCategory}/toggle-status', [ExpenseCategoryController::class, 'toggleStatus'])->name('expense-categories.toggle-status');
    Route::get('expense-categories-analytics', [ExpenseCategoryController::class, 'analytics'])->name('expense-categories.analytics');

    // Additional Accounting Routes
    Route::get('accounts/balance-sheet', [AccountController::class, 'balanceSheet'])->name('accounts.balance-sheet');
    Route::get('accounts/trial-balance', [AccountController::class, 'trialBalance'])->name('accounts.trial-balance');
    Route::get('payments/invoice/{invoice}/details', [PaymentController::class, 'getInvoiceDetails'])->name('payments.invoice-details');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
