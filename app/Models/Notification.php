<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'is_read',
        'action_url',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'task_assigned' => 'fas fa-tasks text-blue-600',
            'task_completed' => 'fas fa-check-circle text-green-600',
            'task_updated' => 'fas fa-edit text-yellow-600',
            'project_updated' => 'fas fa-project-diagram text-purple-600',
            'team_member_added' => 'fas fa-user-plus text-green-600',
            'team_member_removed' => 'fas fa-user-minus text-red-600',
            'deadline_approaching' => 'fas fa-clock text-orange-600',
            'project_completed' => 'fas fa-flag-checkered text-green-600',
            default => 'fas fa-bell text-gray-600',
        };
    }

    public static function createForUsers(array $userIds, array $data): void
    {
        foreach ($userIds as $userId) {
            static::create(array_merge($data, ['user_id' => $userId]));
        }
    }

    public static function createForProjectTeam(Project $project, array $data): void
    {
        $teamMemberIds = $project->activeTeamMembers->pluck('employee_id')->toArray();
        if (!empty($teamMemberIds)) {
            static::createForUsers($teamMemberIds, array_merge($data, ['project_id' => $project->id]));
        }
    }
}
