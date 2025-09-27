<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Plant\Department\TenantUser\Status;
use App\Models\Tenant\Department;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\DepartmentTenantUser>
 */
class DepartmentTenantUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = TenantUser::inRandomOrder()->first();

        $department = Department::active()->where('tenant_id', $user->tenant_id)->inRandomOrder()->first();

        return [
            'status' => fake()->randomElement(Status::cases()),
            'tuser_id' => $user->id,
            'department_id' => $department->id,
            'tenant_id' => $user->tenant_id,
        ];
    }
}
