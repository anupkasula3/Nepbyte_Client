<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'client_id',
        'project_manager_id',
        'status',
        'priority',
        'budget',
        'actual_cost',
        'start_date',
        'end_date',
        'actual_end_date',
        'progress_percentage',
        'technologies_used',
        'requirements',
        'notes'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'progress_percentage' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'project_manager_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class);
    }

    public function activeTeamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class)->where('is_active', true);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class)->orderBy('created_at', 'desc');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'project_team_members')
                    ->withPivot(['role', 'joined_at', 'left_at', 'is_active', 'responsibilities'])
                    ->withTimestamps();
    }

    public function activeTeam(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'project_team_members')
                    ->withPivot(['role', 'joined_at', 'left_at', 'is_active', 'responsibilities'])
                    ->wherePivot('is_active', true)
                    ->withTimestamps();
    }
}
