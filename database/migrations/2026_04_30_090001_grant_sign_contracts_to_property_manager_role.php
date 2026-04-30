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

        $permission = Permission::query()->where('name', 'sign_contracts')->first();
        $role = Role::query()->where('name', 'property_manager')->first();

        if ($permission !== null && $role !== null && ! $role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::query()->where('name', 'sign_contracts')->first();
        $role = Role::query()->where('name', 'property_manager')->first();

        if ($permission !== null && $role !== null && $role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        }
    }
};
