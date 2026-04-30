<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $demoAccounts = [];

        if (app()->environment('local')) {
            $demoAccounts = [
                [
                    'label' => 'Platform admin',
                    'hint' => 'Service provider — /admin',
                    'email' => 'admin@test.com',
                    'password' => 'password',
                ],
                [
                    'label' => 'Company admin',
                    'hint' => 'Property company — Premium Properties',
                    'email' => 'company@test.com',
                    'password' => 'password',
                ],
                [
                    'label' => 'Property manager',
                    'hint' => 'Operations — same company portal',
                    'email' => 'manager@test.com',
                    'password' => 'password',
                ],
                [
                    'label' => 'Tenant',
                    'hint' => 'Resident portal',
                    'email' => 'tenant@test.com',
                    'password' => 'password',
                ],
                [
                    'label' => 'Tenant (student)',
                    'hint' => 'Student Housing SA',
                    'email' => 'student@test.com',
                    'password' => 'password',
                ],
            ];
        }

        return view('auth.login', compact('demoAccounts'));
    }

    /**
     * Handle a login request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (! $user instanceof User) {
                Auth::logout();

                throw ValidationException::withMessages([
                    'email' => 'Unable to authenticate this account at the moment.',
                ]);
            }

            // Check if user is active
            if (! $user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated. Please contact support.',
                ]);
            }

            // Redirect based on user type
            return $this->redirectBasedOnUserType($user);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Redirect user based on their type.
     */
    protected function redirectBasedOnUserType(User $user): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to($user->portalDashboardUrl());
    }
}
