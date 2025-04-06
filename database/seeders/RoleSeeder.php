<?php

namespace Database\Seeders;

use App\Enums\Roles\UserRolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserRolesEnum::cases() as $role) {
            Role::firstOrCreate([
                'name' => $role
            ], [
                'name' => $role
            ]);
        }
    }
}
