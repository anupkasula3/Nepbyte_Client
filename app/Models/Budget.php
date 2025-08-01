<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'description',
        'period_type',
        'start_date',
        'end_date',
        'total_budget',
        'total_actual',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_budget' => 'decimal:2',
        'total_actual' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByPeriod($query, $periodType)
    {
        return $query->where('period_type', $periodType);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'active' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTotalVarianceAttribute()
    {
        return $this->total_actual - $this->total_budget;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->total_budget == 0) {
            return 0;
        }
        
        return (($this->total_actual - $this->total_budget) / $this->total_budget) * 100;
    }

    public function updateActuals()
    {
        $this->total_actual = $this->items()->sum('actual_amount');
        $this->save();

        // Update variance for each item
        foreach ($this->items as $item) {
            $item->variance = $item->actual_amount - $item->budgeted_amount;
            $item->save();
        }
    }

    public function isOverBudget()
    {
        return $this->total_actual > $this->total_budget;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_budget == 0) {
            return 0;
        }
        
        return min(($this->total_actual / $this->total_budget) * 100, 100);
    }
}
