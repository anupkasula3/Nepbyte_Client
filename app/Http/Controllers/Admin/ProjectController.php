<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::with(['client', 'projectManager']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('client') && $request->get('client') != '') {
            $query->where('client_id', $request->get('client'));
        }

        $projects = $query->paginate(15);
        $clients = Client::all();

        return view('admin.projects.index', compact('projects', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $employees = Employee::where('status', 'active')->get();

        return view('admin.projects.create', compact('clients', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:employees,id',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'technologies_used' => 'nullable|string',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'projectManager', 'tasks.assignedEmployee']);

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::where('status', 'active')->get();
        $employees = Employee::where('status', 'active')->get();

        return view('admin.projects.edit', compact('project', 'clients', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:employees,id',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'budget' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'technologies_used' => 'nullable|string',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->tasks()->count() > 0) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'Cannot delete project with tasks. Please complete or reassign tasks first.');
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
