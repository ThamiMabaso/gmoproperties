<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $company = $this->resolveCompanyFromRoute($request);

        // Service provider admins have access to all companies
        if ($user->isServiceProviderAdmin()) {
            if ($company !== null) {
                $request->merge(['company' => $company]);
            }

            return $next($request);
        }

        // For other users, ensure they have a company_id
        if (! $user->company_id) {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Your account is not associated with a company.');
        }

        if ($company !== null) {
            // Ensure user's company matches the URL company
            if ($user->company_id !== $company->id) {
                abort(403, 'You do not have access to this company.');
            }

            $request->merge(['company' => $company]);
        } else {
            // Load user's company if not in URL
            $fallbackCompany = $user->company;
            if ($fallbackCompany) {
                $request->merge(['company' => $fallbackCompany]);
            }
        }

        // Set company_id for scoping
        $request->merge(['company_id' => $user->company_id]);

        return $next($request);
    }

    /**
     * Route model binding may pass a Company instance; numeric URLs use id; otherwise slug.
     */
    private function resolveCompanyFromRoute(Request $request): ?Company
    {
        $value = $request->route('company');

        if ($value instanceof Company) {
            return $value;
        }

        if ($value === null || $value === '') {
            return null;
        }

        $str = (string) $value;

        $table = (new Company())->getTable();

        $bySlug = Company::query()->where($table . '.slug', $str)->first();

        if ($bySlug !== null) {
            return $bySlug;
        }

        if (ctype_digit($str)) {
            return Company::query()->whereKey((int) $str)->first();
        }

        return null;
    }
}
