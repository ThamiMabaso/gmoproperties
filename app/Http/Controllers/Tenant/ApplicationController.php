<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\TenantApplication;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /**
     * Display a listing of available units for application.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $companies = Company::where('is_active', true)->get();
        $units = Unit::where('status', 'available')
            ->with(['building', 'company'])
            ->paginate(12);

        return view('tenant.applications.index', compact('companies', 'units'));
    }

    /**
     * Show the form for creating a new application.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function create(Unit $unit)
    {
        // Check if user already has an active application
        $existingApplication = TenantApplication::where('email', Auth::check() ? Auth::user()->email : request('email'))
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingApplication) {
            return redirect()->route('tenant.applications.show', $existingApplication)
                ->with('info', 'You already have a pending application.');
        }

        return view('tenant.applications.create', compact('unit'));
    }

    /**
     * Store a newly created application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Unit $unit)
    {
        $applicationType = $request->input('application_type', 'working_tenant');

        $rules = [
            'application_type' => ['required', Rule::in(['student', 'working_tenant'])],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'id_number' => 'required|string|unique:tenant_applications,id_number',
            'lease_start_date' => 'required|date|after_or_equal:today',
            'lease_end_date' => 'required|date|after:lease_start_date',
        ];

        // Student-specific fields
        if ($applicationType === 'student') {
            $rules['student_number'] = 'nullable|string|max:255';
            $rules['source_of_funding'] = 'required|string|max:255';
            $rules['proof_of_registration'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        // Working tenant-specific fields
        if ($applicationType === 'working_tenant') {
            $rules['employment_type'] = ['required', Rule::in(['employed', 'self_employed'])];
            if ($request->input('employment_type') === 'employed') {
                $rules['proof_of_income'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            }
        }

        // Common fields
        $rules['id_document'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        $rules['next_of_kin_name'] = 'required|string|max:255';
        $rules['next_of_kin_phone'] = 'required|string|max:20';
        $rules['next_of_kin_relationship'] = 'required|string|max:255';

        $validated = $request->validate($rules);

        // Check for existing application with same ID number
        $existingApplication = TenantApplication::where('id_number', $validated['id_number'])
            ->whereIn('status', ['pending', 'under_review', 'approved'])
            ->first();

        if ($existingApplication) {
            return back()->withErrors(['id_number' => 'An application with this ID number already exists.'])->withInput();
        }

        // Create application
        $application = TenantApplication::create([
            'company_id' => $unit->company_id,
            'unit_id' => $unit->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'id_number' => $validated['id_number'],
            'application_type' => $applicationType,
            'student_number' => $validated['student_number'] ?? null,
            'employment_type' => $validated['employment_type'] ?? null,
            'source_of_funding' => $validated['source_of_funding'] ?? null,
            'lease_start_date' => $validated['lease_start_date'],
            'lease_end_date' => $validated['lease_end_date'],
            'next_of_kin_details' => [
                'name' => $validated['next_of_kin_name'],
                'phone' => $validated['next_of_kin_phone'],
                'relationship' => $validated['next_of_kin_relationship'],
            ],
            'status' => 'pending',
        ]);

        // Handle file uploads
        if ($request->hasFile('id_document')) {
            $path = $request->file('id_document')->store('documents/applications/' . $application->id, 'public');
            $application->documents()->create([
                'company_id' => $unit->company_id,
                'name' => 'ID Document',
                'file_path' => $path,
                'file_type' => $request->file('id_document')->getMimeType(),
                'file_size' => $request->file('id_document')->getSize(),
                'document_type' => 'id_document',
            ]);
        }

        if ($request->hasFile('proof_of_registration')) {
            $path = $request->file('proof_of_registration')->store('documents/applications/' . $application->id, 'public');
            $application->documents()->create([
                'company_id' => $unit->company_id,
                'name' => 'Proof of Registration',
                'file_path' => $path,
                'file_type' => $request->file('proof_of_registration')->getMimeType(),
                'file_size' => $request->file('proof_of_registration')->getSize(),
                'document_type' => 'proof_of_registration',
            ]);
        }

        if ($request->hasFile('proof_of_income')) {
            $path = $request->file('proof_of_income')->store('documents/applications/' . $application->id, 'public');
            $application->documents()->create([
                'company_id' => $unit->company_id,
                'name' => 'Proof of Income',
                'file_path' => $path,
                'file_type' => $request->file('proof_of_income')->getMimeType(),
                'file_size' => $request->file('proof_of_income')->getSize(),
                'document_type' => 'proof_of_income',
            ]);
        }

        // TODO: Send notification email to company admin

        return redirect()->route('tenant.applications.show', $application)
            ->with('success', 'Your application has been submitted successfully. You will be notified once it is reviewed.');
    }

    /**
     * Display the specified application.
     *
     * @param  \App\Models\TenantApplication  $application
     * @return \Illuminate\View\View
     */
    public function show(TenantApplication $application)
    {
        $application->load(['unit.building', 'company', 'documents']);

        return view('tenant.applications.show', compact('application'));
    }
}
