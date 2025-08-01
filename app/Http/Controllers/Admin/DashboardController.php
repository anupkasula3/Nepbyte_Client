<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'total_departments' => Department::count(),
            'active_departments' => Department::where('is_active', true)->count(),
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::whereIn('status', ['planning', 'in_progress'])->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::whereIn('status', ['todo', 'in_progress'])->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'total_equipment' => Equipment::count(),
            'available_equipment' => Equipment::where('status', 'available')->count(),
            'assigned_equipment' => Equipment::where('status', 'assigned')->count(),
        ];

        $recent_projects = Project::with(['client', 'projectManager'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_employees = Employee::with('department')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcoming_tasks = Task::with(['project', 'assignedEmployee'])
            ->where('status', '!=', 'completed')
            ->where('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_projects', 'recent_employees', 'upcoming_tasks'));
    }
}
