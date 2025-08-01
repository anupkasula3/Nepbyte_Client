<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'asset_tag',
        'serial_number',
        'model',
        'manufacturer',
        'category',
        'status',
        'assigned_to',
        'purchase_date',
        'purchase_price',
        'warranty_expiry',
        'location',
        'specifications',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }
}
