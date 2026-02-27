<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'contract_id',
        'tenant_id',
        'unit_id',
        'invoice_number',
        'type',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'status',
        'description',
        'line_items',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'line_items' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the company for this invoice.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the contract for this invoice.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the tenant for this invoice.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Get the unit for this invoice.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get all payments for this invoice.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all documents for this invoice.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->paid_amount >= $this->total_amount;
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date < now() && !$this->isPaid();
    }

    /**
     * Get remaining balance.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }
}
