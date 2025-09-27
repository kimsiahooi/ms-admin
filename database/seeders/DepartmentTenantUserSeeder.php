<?php

namespace Database\Seeders;

use App\Models\Tenant\Department;
use App\Models\Tenant\DepartmentTenantUser;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DepartmentTenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        foreach ($departments as $department) {
            $users = TenantUser::where('tenant_id', $department->tenant_id)->inRandomOrder()->limit(rand(0, 2))->get();

            foreach ($users as $user) {
                DepartmentTenantUser::factory()->state(new Sequence(fn() => [
                    'tuser_id' => $user->id,
                    'department_id' => $department->id,
                    'tenant_id' => $user->tenant_id,
                ]))->create();
            }
        }
    }
}
