<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'employee_id',
        'role',
        'joined_at',
        'left_at',
        'is_active',
        'responsibilities',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'lead' => 'bg-purple-100 text-purple-800',
            'reviewer' => 'bg-blue-100 text-blue-800',
            'observer' => 'bg-gray-100 text-gray-800',
            default => 'bg-green-100 text-green-800',
        };
    }
}
