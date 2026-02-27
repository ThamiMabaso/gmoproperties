<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
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

            // Check if user is active
            if (!$user->is_active) {
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
     * @param  \Illuminate\Http\Request  $request
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
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnUserType($user)
    {
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

        return redirect()->route('home');
    }
}
