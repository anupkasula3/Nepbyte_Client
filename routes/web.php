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
