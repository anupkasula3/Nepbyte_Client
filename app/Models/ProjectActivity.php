<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProjectActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'activity_type',
        'description',
        'metadata',
        'subject_type',
        'subject_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActivityIconAttribute(): string
    {
        return match($this->activity_type) {
            'task_created' => 'fas fa-plus-circle text-green-600',
            'task_updated' => 'fas fa-edit text-blue-600',
            'task_completed' => 'fas fa-check-circle text-green-600',
            'comment_added' => 'fas fa-comment text-blue-600',
            'file_uploaded' => 'fas fa-file-upload text-purple-600',
            'team_member_added' => 'fas fa-user-plus text-green-600',
            'team_member_removed' => 'fas fa-user-minus text-red-600',
            'project_updated' => 'fas fa-project-diagram text-blue-600',
            default => 'fas fa-info-circle text-gray-600',
        };
    }
}
