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

        foreach (RolePermissionsEnum::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ], [
                'name' => $permission,
            ]);
        }

        $superadmin_permissions = Permission::all();

        $admin_permissions = Permission::whereNotLike('name', '%delete%')->get();

        $superadmin_role->syncPermissions($superadmin_permissions);
        $admin_role->syncPermissions($admin_permissions);

        $this->call([
            // RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
