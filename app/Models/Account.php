<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'subtype',
        'parent_id',
        'opening_balance',
        'current_balance',
        'is_active'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getDebitBalanceAttribute()
    {
        return $this->transactionItems()->sum('debit_amount');
    }

    public function getCreditBalanceAttribute()
    {
        return $this->transactionItems()->sum('credit_amount');
    }

    public function updateBalance()
    {
        $debitTotal = $this->transactionItems()->sum('debit_amount');
        $creditTotal = $this->transactionItems()->sum('credit_amount');

        // For asset and expense accounts, debit increases balance
        // For liability, equity, and income accounts, credit increases balance
        if (in_array($this->type, ['asset', 'expense'])) {
            $this->current_balance = $this->opening_balance + $debitTotal - $creditTotal;
        } else {
            $this->current_balance = $this->opening_balance + $creditTotal - $debitTotal;
        }
        $this->save();
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'asset' => 'bg-green-100 text-green-800',
            'liability' => 'bg-red-100 text-red-800',
            'equity' => 'bg-blue-100 text-blue-800',
            'income' => 'bg-purple-100 text-purple-800',
            'expense' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
