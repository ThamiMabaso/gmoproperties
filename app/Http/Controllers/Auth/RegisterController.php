<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form for companies.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a company registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255|unique:companies,email',
            'company_phone' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Create company
        $company = Company::create([
            'name' => $validated['company_name'],
            'slug' => Str::slug($validated['company_name']),
            'email' => $validated['company_email'],
            'phone' => $validated['company_phone'] ?? null,
            'subscription_plan' => 'basic',
            'is_active' => false, // Requires admin approval
        ]);

        // Create company admin user
        $user = User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => 'company_admin',
            'is_active' => false, // Requires admin approval
        ]);

        // Assign company admin role
        $user->assignRole('company_admin');

        // TODO: Send notification to service provider admin for approval
        // TODO: Send welcome email to company admin

        return redirect()->route('register.success')
            ->with('success', 'Your company registration has been submitted. You will receive an email once your account is approved.');
    }
}
