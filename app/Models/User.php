<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'building_id',
        'name',
        'email',
        'password',
        'phone',
        'type',
        'id_number',
        'student_number',
        'employment_type',
        'next_of_kin_details',
        'source_of_funding',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'next_of_kin_details' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company for this user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Building this property manager is assigned to (company admins typically leave null = all buildings).
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get all contracts as tenant.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'tenant_id');
    }

    /**
     * Get active contract.
     */
    public function activeContract()
    {
        return $this->contracts()->where('status', 'active')->first();
    }

    /**
     * Get all invoices as tenant.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'tenant_id');
    }

    /**
     * Get all payments as tenant.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'tenant_id');
    }

    /**
     * Get all maintenance tickets as tenant.
     */
    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class, 'tenant_id');
    }

    /**
     * Get all tenant applications.
     */
    public function tenantApplications(): HasMany
    {
        return $this->hasMany(TenantApplication::class);
    }

    /**
     * Get all messages sent by this user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get all messages received by this user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get unread messages count.
     */
    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    /**
     * Check if user is service provider admin.
     */
    public function isServiceProviderAdmin(): bool
    {
        return $this->type === 'service_provider_admin';
    }

    /**
     * Check if user is company admin.
     */
    public function isCompanyAdmin(): bool
    {
        return $this->type === 'company_admin';
    }

    /**
     * Check if user is property manager.
     */
    public function isPropertyManager(): bool
    {
        return $this->type === 'property_manager';
    }

    /**
     * Check if user is tenant.
     */
    public function isTenant(): bool
    {
        return $this->type === 'tenant';
    }

    /**
     * Default portal dashboard URL (used after login and for "Portal" navigation).
     */
    public function portalDashboardUrl(): string
    {
        if ($this->isServiceProviderAdmin()) {
            return route('admin.dashboard');
        }

        if ($this->isCompanyAdmin() || $this->isPropertyManager()) {
            $company = $this->company;

            if ($company !== null) {
                return route('company.dashboard', $company);
            }
        }

        if ($this->isTenant()) {
            return route('tenant.dashboard');
        }

        return route('home');
    }

    /**
     * Scope to filter by company.
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
