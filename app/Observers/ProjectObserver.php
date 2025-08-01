<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectActivity;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => auth()->id() ?? $project->project_manager_id ?? 1,
            'activity_type' => 'project_created',
            'description' => "Project '{$project->name}' was created",
            'subject_type' => Project::class,
            'subject_id' => $project->id,
            'metadata' => [
                'project_name' => $project->name,
                'client' => $project->client->company_name,
                'budget' => $project->budget,
                'start_date' => $project->start_date->format('Y-m-d'),
                'end_date' => $project->end_date->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $changes = $project->getChanges();
        $original = $project->getOriginal();

        // Check for status changes
        if (isset($changes['status'])) {
            $oldStatus = $original['status'];
            $newStatus = $changes['status'];

            // Log activity
            ProjectActivity::create([
                'project_id' => $project->id,
                'user_id' => auth()->id() ?? 1,
                'activity_type' => 'project_updated',
                'description' => "Project status changed from {$oldStatus} to {$newStatus}",
                'subject_type' => Project::class,
                'subject_id' => $project->id,
                'metadata' => [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'project_name' => $project->name,
                ],
            ]);

            // Notify team about status change
            if ($newStatus === 'completed') {
                Notification::createForProjectTeam($project, [
                    'type' => 'project_completed',
                    'title' => 'Project Completed',
                    'message' => "Project '{$project->name}' has been completed!",
                    'action_url' => route('admin.projects.show', $project),
                    'data' => [
                        'project_name' => $project->name,
                        'completion_date' => now()->format('Y-m-d'),
                    ],
                ]);
            } else {
                Notification::createForProjectTeam($project, [
                    'type' => 'project_updated',
                    'title' => 'Project Status Updated',
                    'message' => "Project '{$project->name}' status changed to {$newStatus}",
                    'action_url' => route('admin.projects.tracking.dashboard', $project),
                    'data' => [
                        'project_name' => $project->name,
                        'new_status' => $newStatus,
                    ],
                ]);
            }
        }

        // Check for progress changes
        if (isset($changes['progress_percentage'])) {
            $oldProgress = $original['progress_percentage'];
            $newProgress = $changes['progress_percentage'];

            // Only notify for significant progress changes (10% or more)
            if (abs($newProgress - $oldProgress) >= 10) {
                ProjectActivity::create([
                    'project_id' => $project->id,
                    'user_id' => auth()->id() ?? 1,
                    'activity_type' => 'project_updated',
                    'description' => "Project progress updated from {$oldProgress}% to {$newProgress}%",
                    'subject_type' => Project::class,
                    'subject_id' => $project->id,
                    'metadata' => [
                        'old_progress' => $oldProgress,
                        'new_progress' => $newProgress,
                        'project_name' => $project->name,
                    ],
                ]);

                Notification::createForProjectTeam($project, [
                    'type' => 'project_updated',
                    'title' => 'Project Progress Updated',
                    'message' => "Project '{$project->name}' is now {$newProgress}% complete",
                    'action_url' => route('admin.projects.tracking.dashboard', $project),
                    'data' => [
                        'project_name' => $project->name,
                        'progress' => $newProgress,
                    ],
                ]);
            }
        }

        // Check for deadline changes
        if (isset($changes['end_date'])) {
            $oldDate = $original['end_date'];
            $newDate = $changes['end_date'];

            ProjectActivity::create([
                'project_id' => $project->id,
                'user_id' => auth()->id() ?? 1,
                'activity_type' => 'project_updated',
                'description' => "Project deadline changed from {$oldDate} to {$newDate}",
                'subject_type' => Project::class,
                'subject_id' => $project->id,
                'metadata' => [
                    'old_deadline' => $oldDate,
                    'new_deadline' => $newDate,
                    'project_name' => $project->name,
                ],
            ]);

            Notification::createForProjectTeam($project, [
                'type' => 'project_updated',
                'title' => 'Project Deadline Changed',
                'message' => "Project '{$project->name}' deadline has been updated to " . \Carbon\Carbon::parse($newDate)->format('M d, Y'),
                'action_url' => route('admin.projects.tracking.dashboard', $project),
                'data' => [
                    'project_name' => $project->name,
                    'new_deadline' => $newDate,
                ],
            ]);
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        // Notify team about project deletion
        Notification::createForProjectTeam($project, [
            'type' => 'project_updated',
            'title' => 'Project Deleted',
            'message' => "Project '{$project->name}' has been deleted",
            'action_url' => route('admin.projects.index'),
            'data' => [
                'project_name' => $project->name,
            ],
        ]);
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
