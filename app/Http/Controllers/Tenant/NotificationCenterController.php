<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationCenterController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $notifications = $user->notifications()->paginate(20);

        return view('tenant.notifications.index', compact('notifications'));
    }

    public function markRead(string $id): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $notification = $user->notifications()->whereKey($id)->firstOrFail();
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead(): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
