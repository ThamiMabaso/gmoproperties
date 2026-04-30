<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Optional unified portal dashboard (wire in routes if used).
 * Loads visible announcements using the same rules as company/tenant portals.
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $announcements = Announcement::query()
            ->publishedVisible()
            ->when(
                $user->company_id !== null,
                static fn ($query) => $query->where('company_id', $user->company_id)
            )
            ->with(['company', 'building'])
            ->orderByDesc('priority')
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        return view('portal.dashboard', [
            'announcements' => $announcements,
        ]);
    }
}
