<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
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
     * Check if user has permission.
     */
    protected function checkPermission(string $permission): void
    {
        $user = Auth::user();

        if (!$user->can($permission)) {
            abort(403, "You do not have permission to {$permission}.");
        }
    }
}
