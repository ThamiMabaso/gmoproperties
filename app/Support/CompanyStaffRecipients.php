<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Collection;

final class CompanyStaffRecipients
{
    /**
     * Company admins and property managers for a building (admins always included).
     *
     * @return Collection<int, User>
     */
    public static function forBuilding(Company $company, ?int $buildingId): Collection
    {
        $query = User::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->whereIn('type', ['company_admin', 'property_manager']);

        if ($buildingId === null) {
            return $query->get()->unique('id')->values();
        }

        return $query
            ->where(function ($sub) use ($buildingId): void {
                $sub->where('type', 'company_admin')
                    ->orWhere(function ($pm) use ($buildingId): void {
                        $pm->where('type', 'property_manager')
                            ->where('building_id', $buildingId);
                    });
            })
            ->get()
            ->unique('id')
            ->values();
    }
}
