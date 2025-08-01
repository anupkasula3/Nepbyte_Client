<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'website',
        'industry',
        'client_type',
        'status',
        'notes',
        'total_contract_value',
        'contract_start_date',
        'contract_end_date'
    ];

    protected $casts = [
        'total_contract_value' => 'decimal:2',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
