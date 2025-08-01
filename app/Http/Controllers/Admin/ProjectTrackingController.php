<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\ProjectTeamMember;
use Illuminate\Http\Request;

class ProjectTrackingController extends Controller
{
    public function dashboard(Project $project)
    {
        $project->load([
            'client',
            'projectManager',
            'activeTeamMembers.employee.department',
            'tasks.assignedEmployee',
            'activities.user'
        ]);

        // Get project statistics
        $stats = [
            'total_tasks' => $project->tasks->count(),
            'completed_tasks' => $project->tasks->where('status', 'completed')->count(),
            'in_progress_tasks' => $project->tasks->where('status', 'in_progress')->count(),
            'overdue_tasks' => $project->tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
            'team_members' => $project->activeTeamMembers->count(),
            'total_estimated_hours' => $project->tasks->sum('estimated_hours'),
            'total_actual_hours' => $project->tasks->sum('actual_hours'),
        ];

        // Get recent activities
        $recentActivities = $project->activities()->with('user')->limit(20)->get();

        // Get task distribution by team member
        $taskDistribution = $project->tasks()
            ->with('assignedEmployee')
            ->get()
            ->groupBy('assigned_to')
            ->map(function ($tasks) {
                $employee = $tasks->first()->assignedEmployee;
                return [
                    'employee' => $employee,
                    'total' => $tasks->count(),
                    'completed' => $tasks->where('status', 'completed')->count(),
                    'in_progress' => $tasks->where('status', 'in_progress')->count(),
                    'todo' => $tasks->where('status', 'todo')->count(),
                ];
            });

        // Get upcoming deadlines
        $upcomingDeadlines = $project->tasks()
            ->with('assignedEmployee')
            ->where('status', '!=', 'completed')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        return view('admin.projects.tracking.dashboard', compact(
            'project',
            'stats',
            'recentActivities',
            'taskDistribution',
            'upcomingDeadlines'
        ));
    }

    public function manageTeam(Project $project)
    {
        $project->load(['activeTeamMembers.employee.department']);
        $availableEmployees = Employee::where('status', 'active')
            ->whereNotIn('id', $project->activeTeamMembers->pluck('employee_id'))
            ->with('department')
            ->get();

        return view('admin.projects.tracking.team', compact('project', 'availableEmployees'));
    }

    public function addTeamMember(Request $request, Project $project)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'role' => 'required|in:member,lead,reviewer,observer',
            'responsibilities' => 'nullable|string',
        ]);

        // Check if employee is already in the team
        $existingMember = $project->teamMembers()
            ->where('employee_id', $validated['employee_id'])
            ->where('is_active', true)
            ->first();

        if ($existingMember) {
            return back()->with('error', 'Employee is already a team member.');
        }

        $teamMember = $project->teamMembers()->create($validated);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => auth()->id() ?? 1, // Fallback for seeding
            'activity_type' => 'team_member_added',
            'description' => "Added {$teamMember->employee->full_name} to the team as {$teamMember->role}",
            'subject_type' => ProjectTeamMember::class,
            'subject_id' => $teamMember->id,
        ]);

        return back()->with('success', 'Team member added successfully.');
    }

    public function removeTeamMember(Project $project, ProjectTeamMember $teamMember)
    {
        $teamMember->update([
            'is_active' => false,
            'left_at' => now(),
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => auth()->id() ?? 1,
            'activity_type' => 'team_member_removed',
            'description' => "Removed {$teamMember->employee->full_name} from the team",
            'subject_type' => ProjectTeamMember::class,
            'subject_id' => $teamMember->id,
        ]);

        return back()->with('success', 'Team member removed successfully.');
    }

    public function updateTeamMember(Request $request, Project $project, ProjectTeamMember $teamMember)
    {
        $validated = $request->validate([
            'role' => 'required|in:member,lead,reviewer,observer',
            'responsibilities' => 'nullable|string',
        ]);

        $oldRole = $teamMember->role;
        $teamMember->update($validated);

        // Log activity if role changed
        if ($oldRole !== $validated['role']) {
            ProjectActivity::create([
                'project_id' => $project->id,
                'user_id' => auth()->id() ?? 1,
                'activity_type' => 'team_member_updated',
                'description' => "Updated {$teamMember->employee->full_name}'s role from {$oldRole} to {$validated['role']}",
                'subject_type' => ProjectTeamMember::class,
                'subject_id' => $teamMember->id,
            ]);
        }

        return back()->with('success', 'Team member updated successfully.');
    }

    public function timeline(Project $project)
    {
        $activities = $project->activities()
            ->with(['user', 'subject'])
            ->paginate(50);

        return view('admin.projects.tracking.timeline', compact('project', 'activities'));
    }
}
