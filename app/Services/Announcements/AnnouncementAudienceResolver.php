<?php

declare(strict_types=1);

namespace App\Services\Announcements;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Collection;

final class AnnouncementAudienceResolver
{
    /**
     * Users who should receive an announcement notification.
     *
     * @return Collection<int, User>
     */
    public function notificationRecipients(Announcement $announcement): Collection
    {
        $announcement->loadMissing('company');

        $company = $announcement->company;

        if ($company === null) {
            return collect();
        }

        $query = User::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->whereIn('type', ['company_admin', 'property_manager', 'tenant']);

        if ($announcement->building_id !== null) {
            $buildingId = (int) $announcement->building_id;

            $query->where(function ($outer) use ($buildingId): void {
                $outer->where('type', 'company_admin')
                    ->orWhere(function ($pm) use ($buildingId): void {
                        $pm->where('type', 'property_manager')
                            ->where('building_id', $buildingId);
                    })
                    ->orWhere(function ($tenant) use ($buildingId): void {
                        $tenant->where('type', 'tenant')
                            ->whereHas('contracts', function ($c) use ($buildingId): void {
                                $c->whereHas('unit', function ($u) use ($buildingId): void {
                                    $u->where('building_id', $buildingId);
                                });
                            });
                    });
            });
        }

        return $query->get()->unique('id')->values();
    }
}
