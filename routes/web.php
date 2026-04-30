<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UpdatesReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Company\AnnouncementController as CompanyAnnouncementController;
use App\Http\Controllers\Company\BuildingController;
use App\Http\Controllers\Company\CompanyController as CompanySettingsController;
use App\Http\Controllers\Company\CompanyUserController;
use App\Http\Controllers\Company\ContractController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\FinancialReportController;
use App\Http\Controllers\Company\InvoiceController;
use App\Http\Controllers\Company\MaintenanceTicketController;
use App\Http\Controllers\Company\MessageController as CompanyMessageController;
use App\Http\Controllers\Company\NotificationCenterController as CompanyNotificationCenterController;
use App\Http\Controllers\Company\NotificationPreferenceController as CompanyNotificationPreferenceController;
use App\Http\Controllers\Company\TenantApplicationController;
use App\Http\Controllers\Company\UnitController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Tenant\AnnouncementController as TenantAnnouncementController;
use App\Http\Controllers\Tenant\ApplicationController as TenantApplicationControllerAlias;
use App\Http\Controllers\Tenant\ContractController as TenantContractController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\InvoiceController as TenantInvoiceController;
use App\Http\Controllers\Tenant\MaintenanceTicketController as TenantMaintenanceTicketController;
use App\Http\Controllers\Tenant\MessageController as TenantMessageController;
use App\Http\Controllers\Tenant\NotificationCenterController as TenantNotificationCenterController;
use App\Http\Controllers\Tenant\NotificationPreferenceController as TenantNotificationPreferenceController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\VisionController;
use Illuminate\Support\Facades\Route;

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

// Friendly aliases (bookmark / MCP — portal login is the same as /login)
Route::redirect('/portal', '/login', 302);
Route::redirect('/portal/login', '/login', 302);

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
| Platform search (service provider admin — uses real Company/Building/Unit/User tables)
|--------------------------------------------------------------------------
*/

Route::get('/search', [SearchController::class, 'search'])
    ->middleware(['auth', 'role:service_provider_admin'])
    ->name('search');

/*
|--------------------------------------------------------------------------
| Service Provider Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:service_provider_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', AdminCompanyController::class);

    Route::get('/reports/updates', [UpdatesReportController::class, 'index'])->name('reports.updates.index');
    Route::get('/reports/updates/export/csv', [UpdatesReportController::class, 'exportCsv'])->name('reports.updates.export.csv');
    Route::get('/reports/updates/export/pdf', [UpdatesReportController::class, 'exportPdf'])->name('reports.updates.export.pdf');
});

/*
|--------------------------------------------------------------------------
| Tenant Portal Routes (must be registered BEFORE {company} so /tenant/* is not captured as a company slug)
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
    Route::get('/contracts/{contract}/download', [TenantContractController::class, 'download'])->name('contracts.download');
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

    Route::get('/announcements', [TenantAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [TenantAnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('/announcements/{announcement}/read', [TenantAnnouncementController::class, 'markRead'])->name('announcements.read');

    Route::middleware('can:view_notifications')->group(function () {
        Route::get('/notifications', [TenantNotificationCenterController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [TenantNotificationCenterController::class, 'markAllRead'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read', [TenantNotificationCenterController::class, 'markRead'])->name('notifications.read');
    });

    Route::middleware('can:manage_notification_preferences')->group(function () {
        Route::get('/notification-preferences', [TenantNotificationPreferenceController::class, 'edit'])->name('notification-preferences.edit');
        Route::put('/notification-preferences', [TenantNotificationPreferenceController::class, 'update'])->name('notification-preferences.update');
    });
});

/*
|--------------------------------------------------------------------------
| Client Admin Portal Routes (Property Company)
|--------------------------------------------------------------------------
*/

Route::prefix('{company}')
    ->where(['company' => '^(?!tenant$|admin$|login$|register$|api$)[\w\-]+$'])
    ->name('company.')
    ->middleware(['auth', 'company', 'role:company_admin|property_manager'])
    ->group(function () {
        Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

        // Company profile (company admins only — enforced in controller)
        Route::get('/settings', [CompanySettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [CompanySettingsController::class, 'update'])->name('settings.update');

        // Team & users (company admins only — enforced in controller)
        Route::get('/users', [CompanyUserController::class, 'index'])->name('users.index');
        Route::post('/users/managers', [CompanyUserController::class, 'storeManager'])->name('users.managers.store');
        Route::patch('/users/{user}', [CompanyUserController::class, 'update'])->name('users.update');

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
        Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
        Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
        Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])->name('contracts.download');
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

        Route::get('/announcements', [CompanyAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [CompanyAnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [CompanyAnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}', [CompanyAnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('/announcements/{announcement}/edit', [CompanyAnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{announcement}', [CompanyAnnouncementController::class, 'update'])->name('announcements.update');
        Route::post('/announcements/{announcement}/read', [CompanyAnnouncementController::class, 'markRead'])->name('announcements.read');

        Route::get('/notifications', [CompanyNotificationCenterController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [CompanyNotificationCenterController::class, 'markAllRead'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read', [CompanyNotificationCenterController::class, 'markRead'])->name('notifications.read');

        Route::get('/notification-preferences', [CompanyNotificationPreferenceController::class, 'edit'])->name('notification-preferences.edit');
        Route::put('/notification-preferences', [CompanyNotificationPreferenceController::class, 'update'])->name('notification-preferences.update');
    });
