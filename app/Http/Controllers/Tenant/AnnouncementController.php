<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ($user->company_id === null) {
            abort(403, 'No company assigned.');
        }

        $announcements = Announcement::query()
            ->where('company_id', $user->company_id)
            ->publishedVisible()
            ->with(['creator', 'building'])
            ->orderByDesc('priority')
            ->orderByDesc('published_at')
            ->paginate(15);

        return view('tenant.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement): View
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ((int) $announcement->company_id !== (int) $user->company_id) {
            abort(403);
        }

        abort_unless($announcement->is_published, 404);

        $announcement->load(['creator', 'building']);

        return view('tenant.announcements.show', compact('announcement'));
    }

    public function markRead(Announcement $announcement): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ((int) $announcement->company_id !== (int) $user->company_id) {
            abort(403);
        }

        AnnouncementRead::query()->updateOrCreate(
            [
                'announcement_id' => $announcement->id,
                'user_id' => $user->id,
            ],
            [
                'read_at' => now(),
            ]
        );

        return back()->with('success', 'Marked as read.');
    }
}
