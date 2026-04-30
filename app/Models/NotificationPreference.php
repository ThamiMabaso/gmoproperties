<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email_applications',
        'email_contracts',
        'email_invoices',
        'email_maintenance',
        'email_announcements',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_applications' => 'boolean',
        'email_contracts' => 'boolean',
        'email_invoices' => 'boolean',
        'email_maintenance' => 'boolean',
        'email_announcements' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
