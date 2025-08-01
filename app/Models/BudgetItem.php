<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    protected $fillable = [
        'budget_id',
        'account_id',
        'expense_category_id',
        'item_name',
        'description',
        'budgeted_amount',
        'actual_amount',
        'variance'
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->budgeted_amount == 0) {
            return 0;
        }
        
        return (($this->actual_amount - $this->budgeted_amount) / $this->budgeted_amount) * 100;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->budgeted_amount == 0) {
            return 0;
        }
        
        return min(($this->actual_amount / $this->budgeted_amount) * 100, 100);
    }

    public function isOverBudget()
    {
        return $this->actual_amount > $this->budgeted_amount;
    }

    public function getVarianceColorAttribute()
    {
        if ($this->variance > 0) {
            return 'text-red-600'; // Over budget
        } elseif ($this->variance < 0) {
            return 'text-green-600'; // Under budget
        } else {
            return 'text-gray-600'; // On budget
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->variance = $item->actual_amount - $item->budgeted_amount;
        });

        static::saved(function ($item) {
            $item->budget->updateActuals();
        });

        static::deleted(function ($item) {
            $item->budget->updateActuals();
        });
    }
}
