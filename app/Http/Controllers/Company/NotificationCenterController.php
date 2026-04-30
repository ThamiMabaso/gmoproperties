<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationCenterController extends BaseCompanyController
{
    public function index(Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_notifications');

        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('company.notifications.index', compact('company', 'notifications'));
    }

    public function markRead(Company $company, string $id): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_notifications');

        $notification = Auth::user()
            ->notifications()
            ->whereKey($id)
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead(Company $company): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_notifications');

        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
