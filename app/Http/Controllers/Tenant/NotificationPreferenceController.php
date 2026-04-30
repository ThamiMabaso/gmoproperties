<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationPreferenceController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $this->authorizePreference($user);

        $preference = NotificationPreference::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_applications' => true,
                'email_contracts' => true,
                'email_invoices' => true,
                'email_maintenance' => true,
                'email_announcements' => true,
            ]
        );

        return view('tenant.notification-preferences.edit', compact('preference'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $this->authorizePreference($user);

        $preference = NotificationPreference::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_applications' => true,
                'email_contracts' => true,
                'email_invoices' => true,
                'email_maintenance' => true,
                'email_announcements' => true,
            ]
        );

        $preference->update([
            'email_applications' => $request->boolean('email_applications'),
            'email_contracts' => $request->boolean('email_contracts'),
            'email_invoices' => $request->boolean('email_invoices'),
            'email_maintenance' => $request->boolean('email_maintenance'),
            'email_announcements' => $request->boolean('email_announcements'),
        ]);

        return redirect()
            ->route('tenant.notification-preferences.edit')
            ->with('success', 'Notification preferences saved.');
    }

    private function authorizePreference(User $user): void
    {
        abort_unless($user->can('manage_notification_preferences'), 403);
    }
}
