<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Allow property managers to approve / reject tenant applications (same as company admin).
     */
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::query()->where('name', 'approve_applications')->first();

        if ($permission === null) {
            return;
        }

        $role = Role::query()->where('name', 'property_manager')->first();

        if ($role === null) {
            return;
        }

        if (! $role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::query()->where('name', 'approve_applications')->first();
        $role = Role::query()->where('name', 'property_manager')->first();

        if ($permission !== null && $role !== null) {
            $role->revokePermissionTo($permission);
        }
    }
};
