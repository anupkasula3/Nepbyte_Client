<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\ProjectActivity;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        // Log activity
        ProjectActivity::create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id() ?? $task->created_by ?? 1,
            'activity_type' => 'task_created',
            'description' => "Created task: {$task->title}",
            'subject_type' => Task::class,
            'subject_id' => $task->id,
            'metadata' => [
                'task_title' => $task->title,
                'assigned_to' => $task->assignedEmployee->full_name ?? 'Unassigned',
                'priority' => $task->priority,
                'due_date' => $task->due_date->format('Y-m-d'),
            ],
        ]);

        // Create notification for assigned employee
        if ($task->assigned_to) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'project_id' => $task->project_id,
                'type' => 'task_assigned',
                'title' => 'New Task Assigned',
                'message' => "You have been assigned a new task: {$task->title}",
                'action_url' => route('admin.tasks.show', $task),
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'priority' => $task->priority,
                    'due_date' => $task->due_date->format('Y-m-d'),
                ],
            ]);
        }

        // Notify project team about new task
        if ($task->project) {
            $teamMemberIds = $task->project->activeTeamMembers
                ->where('employee_id', '!=', $task->assigned_to)
                ->pluck('employee_id')
                ->toArray();

            if (!empty($teamMemberIds)) {
                Notification::createForUsers($teamMemberIds, [
                    'project_id' => $task->project_id,
                    'type' => 'task_created',
                    'title' => 'New Task Created',
                    'message' => "A new task '{$task->title}' was created in {$task->project->name}",
                    'action_url' => route('admin.projects.tracking.dashboard', $task->project),
                    'data' => [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'project_name' => $task->project->name,
                    ],
                ]);
            }
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $changes = $task->getChanges();
        $original = $task->getOriginal();

        // Check for status changes
        if (isset($changes['status'])) {
            $oldStatus = $original['status'];
            $newStatus = $changes['status'];

            // Log activity for status change
            ProjectActivity::create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id() ?? 1,
                'activity_type' => $newStatus === 'completed' ? 'task_completed' : 'task_updated',
                'description' => "Updated task '{$task->title}' status from {$oldStatus} to {$newStatus}",
                'subject_type' => Task::class,
                'subject_id' => $task->id,
                'metadata' => [
                    'task_title' => $task->title,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'assigned_to' => $task->assignedEmployee->full_name ?? 'Unassigned',
                ],
            ]);

            // Create notifications based on status change
            if ($newStatus === 'completed') {
                // Notify project team about task completion
                Notification::createForProjectTeam($task->project, [
                    'type' => 'task_completed',
                    'title' => 'Task Completed',
                    'message' => "Task '{$task->title}' has been completed by {$task->assignedEmployee->full_name}",
                    'action_url' => route('admin.tasks.show', $task),
                    'data' => [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'completed_by' => $task->assignedEmployee->full_name,
                    ],
                ]);
            } elseif ($newStatus === 'in_progress' && $oldStatus === 'todo') {
                // Notify when task is started
                Notification::createForProjectTeam($task->project, [
                    'type' => 'task_updated',
                    'title' => 'Task Started',
                    'message' => "Work has started on task '{$task->title}'",
                    'action_url' => route('admin.tasks.show', $task),
                    'data' => [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'started_by' => $task->assignedEmployee->full_name,
                    ],
                ]);
            }
        }

        // Check for assignment changes
        if (isset($changes['assigned_to'])) {
            $oldAssignee = $original['assigned_to'];
            $newAssignee = $changes['assigned_to'];

            if ($newAssignee) {
                // Notify new assignee
                Notification::create([
                    'user_id' => $newAssignee,
                    'project_id' => $task->project_id,
                    'type' => 'task_assigned',
                    'title' => 'Task Reassigned to You',
                    'message' => "Task '{$task->title}' has been assigned to you",
                    'action_url' => route('admin.tasks.show', $task),
                    'data' => [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'priority' => $task->priority,
                    ],
                ]);
            }

            // Log activity
            ProjectActivity::create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id() ?? 1,
                'activity_type' => 'task_updated',
                'description' => "Reassigned task '{$task->title}' to {$task->assignedEmployee->full_name}",
                'subject_type' => Task::class,
                'subject_id' => $task->id,
                'metadata' => [
                    'task_title' => $task->title,
                    'new_assignee' => $task->assignedEmployee->full_name,
                ],
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        // Log activity
        ProjectActivity::create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id() ?? 1,
            'activity_type' => 'task_deleted',
            'description' => "Deleted task: {$task->title}",
            'subject_type' => Task::class,
            'subject_id' => $task->id,
            'metadata' => [
                'task_title' => $task->title,
                'was_assigned_to' => $task->assignedEmployee->full_name ?? 'Unassigned',
            ],
        ]);

        // Notify project team
        if ($task->project) {
            Notification::createForProjectTeam($task->project, [
                'type' => 'task_updated',
                'title' => 'Task Deleted',
                'message' => "Task '{$task->title}' has been deleted from {$task->project->name}",
                'action_url' => route('admin.projects.tracking.dashboard', $task->project),
                'data' => [
                    'task_title' => $task->title,
                    'project_name' => $task->project->name,
                ],
            ]);
        }
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
