<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $names = [
            'view_admin_reports',
            'export_admin_reports',
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ];

        foreach ($names as $name) {
            Permission::query()->firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $assign = static function (string $roleName, array $permissionNames): void {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role === null) {
                return;
            }

            foreach ($permissionNames as $permissionName) {
                $permission = Permission::query()->where('name', $permissionName)->first();

                if ($permission !== null && ! $role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        };

        $assign('service_provider_admin', $names);

        $assign('company_admin', [
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ]);

        $assign('property_manager', [
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ]);

        $assign('tenant', [
            'view_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $names = [
            'view_admin_reports',
            'export_admin_reports',
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'view_notifications',
            'manage_notification_preferences',
        ];

        foreach (['service_provider_admin', 'company_admin', 'property_manager', 'tenant'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role === null) {
                continue;
            }

            foreach ($names as $permissionName) {
                $permission = Permission::query()->where('name', $permissionName)->first();

                if ($permission !== null && $role->hasPermissionTo($permission)) {
                    $role->revokePermissionTo($permission);
                }
            }
        }

        foreach ($names as $permissionName) {
            Permission::query()->where('name', $permissionName)->delete();
        }
    }
};
