<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'unit_id',
        'tenant_id',
        'application_id',
        'contract_number',
        'start_date',
        'end_date',
        'monthly_rent',
        'deposit',
        'terms',
        'terms_text',
        'status',
        'signed_at',
        'signed_document_path',
        'signed_by_tenant',
        'signed_by_company',
        'termination_reason',
        'termination_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'deposit' => 'decimal:2',
        'terms' => 'array',
        'signed_at' => 'datetime',
        'termination_date' => 'date',
    ];

    /**
     * Get the company for this contract.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the unit for this contract.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the tenant for this contract.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Get the application that created this contract.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(TenantApplication::class, 'application_id');
    }

    /**
     * Get the user who signed as tenant.
     */
    public function tenantSigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_tenant');
    }

    /**
     * Get the user who signed as company.
     */
    public function companySigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_company');
    }

    /**
     * Get all invoices for this contract.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all documents for this contract.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Check if contract is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->start_date <= now() 
            && $this->end_date >= now();
    }

    /**
     * Check if contract is signed.
     */
    public function isSigned(): bool
    {
        return $this->signed_at !== null 
            && $this->signed_by_tenant !== null 
            && $this->signed_by_company !== null;
    }
}
