<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceTicket extends Model
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
        'ticket_number',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'assigned_by',
        'assigned_at',
        'completed_at',
        'estimated_cost',
        'actual_cost',
        'resolution_notes',
        'rating',
        'tenant_feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /**
     * Get the company for this ticket.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the unit for this ticket.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the tenant who created this ticket.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Get the user assigned to this ticket.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who assigned this ticket.
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the expense related to this ticket.
     */
    public function expense(): HasOne
    {
        return $this->hasOne(Expense::class);
    }

    /**
     * Check if ticket is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if ticket is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
