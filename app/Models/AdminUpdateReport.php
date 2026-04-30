<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUpdateReport extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'filters',
        'format',
        'file_path',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'filters' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
