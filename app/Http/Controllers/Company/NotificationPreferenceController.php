<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Models\NotificationPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationPreferenceController extends BaseCompanyController
{
    public function edit(Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('manage_notification_preferences');

        $preference = NotificationPreference::query()->firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'email_applications' => true,
                'email_contracts' => true,
                'email_invoices' => true,
                'email_maintenance' => true,
                'email_announcements' => true,
            ]
        );

        return view('company.notification-preferences.edit', compact('company', 'preference'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('manage_notification_preferences');

        $preference = NotificationPreference::query()->firstOrCreate(
            ['user_id' => Auth::id()],
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
            ->route('company.notification-preferences.edit', $company)
            ->with('success', 'Notification preferences saved.');
    }
}
