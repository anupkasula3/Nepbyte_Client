<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'head_of_department',
        'employee_count',
        'budget',
        'location',
        'is_active'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
