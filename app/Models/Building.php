<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'address',
        'city',
        'province',
        'postal_code',
        'property_type',
        'total_units',
        'occupied_units',
        'amenities',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the building.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all units in this building.
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Get all expenses for this building.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
