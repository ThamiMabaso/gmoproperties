<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'building_id',
        'unit_id',
        'maintenance_ticket_id',
        'expense_number',
        'category',
        'description',
        'amount',
        'expense_date',
        'vendor',
        'receipt_path',
        'recorded_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * Get the company for this expense.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the building for this expense.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the unit for this expense.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the maintenance ticket for this expense.
     */
    public function maintenanceTicket(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTicket::class);
    }

    /**
     * Get the user who recorded this expense.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
