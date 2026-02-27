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

        if (!$user) {
            return redirect()->route('login');
        }

        // Service provider admins have access to all companies
        if ($user->isServiceProviderAdmin()) {
            // Load company from slug if provided
            $companySlug = $request->route('company');
            if ($companySlug) {
                $company = Company::where('slug', $companySlug)->first();
                if ($company) {
                    $request->merge(['company' => $company]);
                }
            }
            return $next($request);
        }

        // For other users, ensure they have a company_id
        if (!$user->company_id) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is not associated with a company.');
        }

        // Validate that the company slug in URL matches user's company
        $companySlug = $request->route('company');
        if ($companySlug) {
            $company = Company::where('slug', $companySlug)->first();
            
            if (!$company) {
                abort(404, 'Company not found');
            }

            // Ensure user's company matches the URL company
            if ($user->company_id !== $company->id) {
                abort(403, 'You do not have access to this company.');
            }

            // Set company context for the request
            $request->merge(['company' => $company]);
        } else {
            // Load user's company if not in URL
            $company = $user->company;
            if ($company) {
                $request->merge(['company' => $company]);
            }
        }

        // Set company_id for scoping
        $request->merge(['company_id' => $user->company_id]);

        return $next($request);
    }
}
