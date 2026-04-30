<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;
use App\Models\TenantApplication;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

abstract class BaseCompanyController extends Controller
{
    /**
     * Ensure user has access to the company.
     */
    protected function ensureCompanyAccess(Company $company): void
    {
        $user = Auth::user();

        if (!$user->isServiceProviderAdmin() && $user->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this company.');
        }
    }

    /**
     * Only company administrators may manage team membership and manager building assignments.
     */
    protected function ensureCompanyAdminForCompany(Company $company): void
    {
        $user = Auth::user();

        if (! $user->isCompanyAdmin() || (int) $user->company_id !== (int) $company->id) {
            abort(403, 'Only company administrators can manage users and manager assignments.');
        }
    }

    /**
     * Company admins and property managers assigned to the ticket's building may receive assignments.
     *
     * @return Collection<int, User>
     */
    protected function maintenanceTicketAssigneeCandidates(Company $company, MaintenanceTicket $ticket): Collection
    {
        $ticket->loadMissing('unit.building');

        $buildingId = $ticket->unit?->building_id;

        return $company->users()
            ->where('is_active', true)
            ->whereIn('type', ['company_admin', 'property_manager'])
            ->orderBy('name')
            ->get()
            ->filter(function (User $user) use ($buildingId) {
                if ($user->isCompanyAdmin()) {
                    return true;
                }

                if (! $user->isPropertyManager()) {
                    return false;
                }

                if ($buildingId === null) {
                    return false;
                }

                return $user->building_id !== null
                    && (int) $user->building_id === (int) $buildingId;
            })
            ->values();
    }

    /**
     * Check if user has permission.
     */
    protected function checkPermission(string $permission): void
    {
        $user = Auth::user();

        if (!$user->can($permission)) {
            abort(403, "You do not have permission to {$permission}.");
        }
    }

    /**
     * Building IDs this user may manage within the company portal.
     * null = all buildings (company admin or service provider admin).
     * non-null array = restrict to these buildings (property manager with assignment).
     * empty array = no access to building-scoped data until an administrator assigns a building.
     *
     * @return array<int>|null
     */
    protected function managedBuildingIdsFor(Company $company): ?array
    {
        $user = Auth::user();

        if ($user->isServiceProviderAdmin()) {
            return null;
        }

        if ($user->isCompanyAdmin() && (int) $user->company_id === (int) $company->id) {
            return null;
        }

        if ($user->isPropertyManager() && (int) $user->company_id === (int) $company->id) {
            if ($user->building_id === null) {
                return [];
            }

            $building = $user->building;

            if ($building === null || (int) $building->company_id !== (int) $company->id) {
                return [];
            }

            return [(int) $user->building_id];
        }

        return null;
    }

    /**
     * Block access to an application if the unit is outside the manager's assigned building(s).
     */
    protected function ensureTenantApplicationInScope(Company $company, TenantApplication $application): void
    {
        $application->loadMissing('unit');

        if ($application->unit === null) {
            abort(404, 'Application unit not found.');
        }

        $ids = $this->managedBuildingIdsFor($company);

        if ($ids === null) {
            return;
        }

        $buildingId = (int) $application->unit->building_id;

        if ($ids === [] || ! in_array($buildingId, array_map('intval', $ids), true)) {
            abort(403, 'You do not have access to applications outside your assigned building.');
        }
    }

    /**
     * Ensure a building is within the user's managed scope (for property managers).
     */
    protected function ensureBuildingInScope(Company $company, Building $building): void
    {
        if ((int) $building->company_id !== (int) $company->id) {
            abort(403, 'Unauthorized access to this building.');
        }

        $ids = $this->managedBuildingIdsFor($company);

        if ($ids === null) {
            return;
        }

        if ($ids === [] || ! in_array((int) $building->id, array_map('intval', $ids), true)) {
            abort(403, 'You do not have access to this building.');
        }
    }

    /**
     * Ensure a unit belongs to the company and its building is within scope.
     */
    protected function ensureUnitInScope(Company $company, Unit $unit): void
    {
        if ((int) $unit->company_id !== (int) $company->id) {
            abort(403, 'Unauthorized access to this unit.');
        }

        $unit->loadMissing('building');

        if ($unit->building === null) {
            abort(404, 'Unit building not found.');
        }

        $this->ensureBuildingInScope($company, $unit->building);
    }

    /**
     * Property managers may only assign units to buildings they manage.
     *
     * @param  array<int>|null  $managedBuildingIds  Output of managedBuildingIdsFor()
     */
    protected function ensureBuildingIdAllowedForScope(Company $company, int $buildingId, ?array $managedBuildingIds): void
    {
        if (! Building::query()->where('company_id', $company->id)->whereKey($buildingId)->exists()) {
            abort(403, 'Invalid building for this company.');
        }

        if ($managedBuildingIds === null) {
            return;
        }

        if ($managedBuildingIds === [] || ! in_array($buildingId, array_map('intval', $managedBuildingIds), true)) {
            abort(403, 'You cannot assign units outside your managed building.');
        }
    }

    /**
     * Company-wide building CRUD (create/delete) is limited to users with full portfolio access.
     */
    protected function ensureCanManageBuildingDirectory(Company $company): void
    {
        if ($this->managedBuildingIdsFor($company) !== null) {
            abort(403, 'Only company administrators can add or remove buildings.');
        }
    }

    /**
     * Units visible to the current user within this company.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedUnitsQuery(Company $company, ?array $buildingIds): Builder
    {
        $query = $company->units()->getQuery();

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereIn('building_id', $buildingIds);
            }
        }

        return $query;
    }

    /**
     * Buildings visible to the current user within this company.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedBuildingsQuery(Company $company, ?array $buildingIds): Builder
    {
        $query = $company->buildings()->getQuery();

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereIn('id', $buildingIds);
            }
        }

        return $query;
    }

    /**
     * Tenant applications visible to the current user.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedTenantApplicationsQuery(Company $company, ?array $buildingIds): Builder
    {
        $query = $company->tenantApplications()->getQuery();

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereHas('unit', function ($uq) use ($buildingIds) {
                    $uq->whereIn('building_id', $buildingIds);
                });
            }
        }

        return $query;
    }

    /**
     * Maintenance tickets visible to the current user.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedMaintenanceTicketsQuery(Company $company, ?array $buildingIds): Builder
    {
        $query = $company->maintenanceTickets()->getQuery();

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereHas('unit', function ($uq) use ($buildingIds) {
                    $uq->whereIn('building_id', $buildingIds);
                });
            }
        }

        return $query;
    }

    /**
     * Invoices visible to the current user.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedInvoicesQuery(Company $company, ?array $buildingIds): Builder
    {
        $query = $company->invoices()->getQuery();

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereHas('unit', function ($uq) use ($buildingIds) {
                    $uq->whereIn('building_id', $buildingIds);
                });
            }
        }

        return $query;
    }

    /**
     * Contracts visible to the current user.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedContractsQuery(Company $company, ?array $buildingIds): Builder
    {
        $query = $company->contracts()->getQuery();

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereHas('unit', function ($uq) use ($buildingIds) {
                    $uq->whereIn('building_id', $buildingIds);
                });
            }
        }

        return $query;
    }

    /**
     * Count tenant users tied to contracts in the visible unit set.
     *
     * @param  array<int>|null  $buildingIds
     */
    protected function scopedTenantUserCount(Company $company, ?array $buildingIds): int
    {
        if ($buildingIds === null) {
            return $company->users()->where('type', 'tenant')->count();
        }

        if ($buildingIds === []) {
            return 0;
        }

        $unitIds = $company->units()->getQuery()->whereIn('building_id', $buildingIds)->pluck('id');

        if ($unitIds->isEmpty()) {
            return 0;
        }

        return User::query()
            ->where('company_id', $company->id)
            ->where('type', 'tenant')
            ->whereHas('contracts', function ($q) use ($company, $unitIds) {
                $q->where('company_id', $company->id)->whereIn('unit_id', $unitIds);
            })
            ->count();
    }

    /**
     * Maintenance ticket must belong to a unit in the user's building scope.
     */
    protected function ensureMaintenanceTicketInScope(Company $company, MaintenanceTicket $ticket): void
    {
        if ((int) $ticket->company_id !== (int) $company->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->loadMissing('unit.building');

        if ($ticket->unit === null || $ticket->unit->building === null) {
            abort(404, 'Ticket unit not found.');
        }

        $this->ensureBuildingInScope($company, $ticket->unit->building);
    }

    /**
     * Invoice must belong to a unit in the user's building scope.
     */
    protected function ensureInvoiceInScope(Company $company, Invoice $invoice): void
    {
        if ((int) $invoice->company_id !== (int) $company->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $invoice->loadMissing('unit.building');

        if ($invoice->unit === null || $invoice->unit->building === null) {
            abort(404, 'Invoice unit not found.');
        }

        $this->ensureBuildingInScope($company, $invoice->unit->building);
    }
}
