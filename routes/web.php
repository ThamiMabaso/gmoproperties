<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VisionController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\TenantApplicationController;
use App\Http\Controllers\Company\ContractController;
use App\Http\Controllers\Company\InvoiceController;
use App\Http\Controllers\Company\MaintenanceTicketController;
use App\Http\Controllers\Company\FinancialReportController;
use App\Http\Controllers\Company\BuildingController;
use App\Http\Controllers\Company\UnitController;
use App\Http\Controllers\Company\MessageController as CompanyMessageController;
use App\Http\Controllers\Tenant\MessageController as TenantMessageController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\ApplicationController as TenantApplicationControllerAlias;
use App\Http\Controllers\Tenant\ContractController as TenantContractController;
use App\Http\Controllers\Tenant\InvoiceController as TenantInvoiceController;
use App\Http\Controllers\Tenant\MaintenanceTicketController as TenantMaintenanceTicketController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/project', [ProjectController::class, 'index'])->name('project');
Route::get('/vision', [VisionController::class, 'index'])->name('vision');
Route::get('/objective', [ObjectiveController::class, 'index'])->name('objective');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thank-you', [ThankYouController::class, 'index'])->name('thank-you');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/register/success', function () {
        return view('auth.register-success');
    })->name('register.success');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Service Provider Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:service_provider_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', AdminCompanyController::class);
});

/*
|--------------------------------------------------------------------------
| Client Admin Portal Routes (Property Company)
|--------------------------------------------------------------------------
*/

Route::prefix('{company}')->name('company.')->middleware(['auth', 'company', 'role:company_admin|property_manager'])->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

    // Buildings & Units
    Route::resource('buildings', BuildingController::class);
    Route::resource('units', UnitController::class);

    // Tenant Applications
    Route::get('/applications', [TenantApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [TenantApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/approve', [TenantApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [TenantApplicationController::class, 'reject'])->name('applications.reject');

    // Contracts
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/sign', [ContractController::class, 'signAsCompany'])->name('contracts.sign');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/generate-rent', [InvoiceController::class, 'generateRentInvoices'])->name('invoices.generate-rent');

    // Maintenance Tickets
    Route::get('/maintenance', [MaintenanceTicketController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/{ticket}', [MaintenanceTicketController::class, 'show'])->name('maintenance.show');
    Route::post('/maintenance/{ticket}/assign', [MaintenanceTicketController::class, 'assign'])->name('maintenance.assign');
    Route::post('/maintenance/{ticket}/status', [MaintenanceTicketController::class, 'updateStatus'])->name('maintenance.update-status');

    // Financial Reports
    Route::get('/financial', [FinancialReportController::class, 'index'])->name('financial.index');

    // Messages
    Route::get('/messages', [CompanyMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [CompanyMessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [CompanyMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [CompanyMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/read', [CompanyMessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [CompanyMessageController::class, 'destroy'])->name('messages.destroy');
});

/*
|--------------------------------------------------------------------------
| Tenant Portal Routes
|--------------------------------------------------------------------------
*/

Route::prefix('tenant')->name('tenant.')->middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');

    // Applications
    Route::get('/applications', [TenantApplicationControllerAlias::class, 'index'])->name('applications.index');
    Route::get('/applications/{unit}/create', [TenantApplicationControllerAlias::class, 'create'])->name('applications.create');
    Route::post('/applications/{unit}', [TenantApplicationControllerAlias::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [TenantApplicationControllerAlias::class, 'show'])->name('applications.show');

    // Contracts
    Route::get('/contracts', [TenantContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}', [TenantContractController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/sign', [TenantContractController::class, 'sign'])->name('contracts.sign');

    // Invoices
    Route::get('/invoices', [TenantInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [TenantInvoiceController::class, 'show'])->name('invoices.show');

    // Maintenance Tickets
    Route::get('/maintenance', [TenantMaintenanceTicketController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/create', [TenantMaintenanceTicketController::class, 'create'])->name('maintenance.create');
    Route::post('/maintenance', [TenantMaintenanceTicketController::class, 'store'])->name('maintenance.store');
    Route::get('/maintenance/{ticket}', [TenantMaintenanceTicketController::class, 'show'])->name('maintenance.show');

    // Messages
    Route::get('/messages', [TenantMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [TenantMessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [TenantMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [TenantMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/read', [TenantMessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [TenantMessageController::class, 'destroy'])->name('messages.destroy');
});
