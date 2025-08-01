<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'department_id',
        'salary',
        'hire_date',
        'birth_date',
        'address',
        'employment_type',
        'status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'skills',
        'profile_image'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date',
        'birth_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class, 'assigned_to');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
