<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignedEmployee', 'creator']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('project') && $request->get('project') != '') {
            $query->where('project_id', $request->get('project'));
        }

        if ($request->has('assigned_to') && $request->get('assigned_to') != '') {
            $query->where('assigned_to', $request->get('assigned_to'));
        }

        $tasks = $query->paginate(15);
        $projects = Project::all();
        $employees = Employee::where('status', 'active')->get();

        return view('admin.tasks.index', compact('tasks', 'projects', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::whereIn('status', ['planning', 'in_progress'])->get();
        $employees = Employee::where('status', 'active')->get();

        return view('admin.tasks.create', compact('projects', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:employees,id',
            'created_by' => 'required|exists:employees,id',
            'status' => 'required|in:todo,in_progress,review,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'due_date' => 'required|date',
            'estimated_hours' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        Task::create($validated);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignedEmployee', 'creator']);

        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::whereIn('status', ['planning', 'in_progress'])->get();
        $employees = Employee::where('status', 'active')->get();

        return view('admin.tasks.edit', compact('task', 'projects', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:employees,id',
            'status' => 'required|in:todo,in_progress,review,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'due_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:1',
            'actual_hours' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
