<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Bind {company} by slug (e.g. premium-properties) or numeric id (e.g. /1/dashboard).
        // Use qualified columns / whereKey so queries stay valid if global scopes add joins (avoids "Column id is ambiguous").
        Route::bind('company', function (string $value): \App\Models\Company {
            $table = (new \App\Models\Company())->getTable();

            $company = \App\Models\Company::query()->where($table . '.slug', $value)->first();

            if ($company !== null) {
                return $company;
            }

            if (ctype_digit($value)) {
                return \App\Models\Company::query()->whereKey((int) $value)->firstOrFail();
            }

            abort(404);
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
