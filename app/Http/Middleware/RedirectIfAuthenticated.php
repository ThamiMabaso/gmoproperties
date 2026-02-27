<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? ['web'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect based on user type
                if ($user->isServiceProviderAdmin()) {
                    return redirect()->route('admin.dashboard');
                }

                if ($user->isCompanyAdmin() || $user->isPropertyManager()) {
                    $company = $user->company;
                    return redirect()->route('company.dashboard', ['company' => $company->slug]);
                }

                if ($user->isTenant()) {
                    return redirect()->route('tenant.dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
