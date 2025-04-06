<?php

namespace Database\Seeders;

use App\Enums\Permissions\RolePermissionsEnum;
use App\Enums\Roles\UserRolesEnum;
use Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadmin_role = Role::firstOrCreate([
            'name' => UserRolesEnum::SuperAdmin
        ], [
            'name' => UserRolesEnum::SuperAdmin
        ]);

        $admin_role = Role::firstOrCreate([
            'name' => UserRolesEnum::Admin
        ], [
            'name' => UserRolesEnum::Admin
        ]);

        $user_role = Role::firstOrCreate([
            'name' => UserRolesEnum::User
        ], [
            'name' => UserRolesEnum::User
        ]);

        $view_role_permission = Permission::firstOrCreate([
            'name' => RolePermissionsEnum::ViewRole,
        ], [
            'name' => RolePermissionsEnum::ViewRole,
        ]);

        $create_role_permission = Permission::firstOrCreate([
            'name' => RolePermissionsEnum::CreateRole,
        ], [
            'name' => RolePermissionsEnum::CreateRole,
        ]);

        $update_role_permission = Permission::firstOrCreate([
            'name' => RolePermissionsEnum::UpdateRole,
        ], [
            'name' => RolePermissionsEnum::UpdateRole,
        ]);

        $delete_role_permission = Permission::firstOrCreate([
            'name' => RolePermissionsEnum::DeleteRole,
        ], [
            'name' => RolePermissionsEnum::DeleteRole,
        ]);

        $superadmin_role->syncPermissions([$view_role_permission, $create_role_permission, $update_role_permission, $delete_role_permission]);
        $admin_role->syncPermissions([$view_role_permission]);

        $this->call([
            // RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
