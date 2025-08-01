<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'reference',
        'description',
        'total_amount',
        'type',
        'status',
        'created_by',
        'attachments',
        'notes'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'total_amount' => 'decimal:2',
        'attachments' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'posted' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'income' => 'bg-green-100 text-green-800',
            'expense' => 'bg-red-100 text-red-800',
            'transfer' => 'bg-blue-100 text-blue-800',
            'adjustment' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isBalanced()
    {
        $totalDebits = $this->items()->sum('debit_amount');
        $totalCredits = $this->items()->sum('credit_amount');
        
        return abs($totalDebits - $totalCredits) < 0.01; // Allow for small rounding differences
    }

    public function post()
    {
        if ($this->status !== 'draft') {
            throw new \Exception('Only draft transactions can be posted.');
        }

        if (!$this->isBalanced()) {
            throw new \Exception('Transaction is not balanced.');
        }

        $this->status = 'posted';
        $this->save();

        // Update account balances
        foreach ($this->items as $item) {
            $item->account->updateBalance();
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = static::generateTransactionNumber();
            }
        });
    }

    public static function generateTransactionNumber()
    {
        $prefix = 'TXN';
        $year = date('Y');
        $month = date('m');
        
        $lastTransaction = static::where('transaction_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->orderBy('transaction_number', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $newNumber);
    }
}
