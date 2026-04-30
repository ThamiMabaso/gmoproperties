<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Company management
            'view_companies',
            'create_companies',
            'edit_companies',
            'delete_companies',
            'approve_companies',

            // Building management
            'view_buildings',
            'create_buildings',
            'edit_buildings',
            'delete_buildings',

            // Unit management
            'view_units',
            'create_units',
            'edit_units',
            'delete_units',

            // Tenant management
            'view_tenants',
            'create_tenants',
            'edit_tenants',
            'delete_tenants',
            'approve_applications',

            // Contract management
            'view_contracts',
            'create_contracts',
            'edit_contracts',
            'delete_contracts',
            'sign_contracts',

            // Invoice management
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'delete_invoices',

            // Payment management
            'view_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',

            // Maintenance management
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'assign_maintenance',
            'complete_maintenance',

            // Expense management
            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'delete_expenses',

            // Financial reports
            'view_financial_reports',
            'export_financial_reports',

            // Admin reports & portal communications
            'view_admin_reports',
            'export_admin_reports',
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',

            // System administration
            'manage_system',
            'view_analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Service Provider Admin - Full access
        $serviceProviderAdmin = Role::create(['name' => 'service_provider_admin']);
        $serviceProviderAdmin->givePermissionTo(Permission::all());

        // Company Admin - Full access to their company
        $companyAdmin = Role::create(['name' => 'company_admin']);
        $companyAdmin->givePermissionTo([
            'view_companies',
            'view_buildings',
            'create_buildings',
            'edit_buildings',
            'delete_buildings',
            'view_units',
            'create_units',
            'edit_units',
            'delete_units',
            'view_tenants',
            'create_tenants',
            'edit_tenants',
            'approve_applications',
            'view_contracts',
            'create_contracts',
            'edit_contracts',
            'sign_contracts',
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'view_payments',
            'create_payments',
            'edit_payments',
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'assign_maintenance',
            'complete_maintenance',
            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'view_financial_reports',
            'export_financial_reports',
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ]);

        // Property Manager - Limited access
        $propertyManager = Role::create(['name' => 'property_manager']);
        $propertyManager->givePermissionTo([
            'view_buildings',
            'view_units',
            'view_tenants',
            'approve_applications',
            'view_contracts',
            'sign_contracts',
            'view_invoices',
            'create_invoices',
            'view_payments',
            'create_payments',
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'assign_maintenance',
            'complete_maintenance',
            'view_expenses',
            'create_expenses',
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ]);

        // Tenant - Limited access to their own data
        $tenant = Role::create(['name' => 'tenant']);
        $tenant->givePermissionTo([
            'view_contracts',
            'sign_contracts',
            'view_invoices',
            'view_payments',
            'create_maintenance',
            'view_maintenance',
            'view_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ]);
    }
}
