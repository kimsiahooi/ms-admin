<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Plant\Operation\TenantUser\Status;
use App\Models\Tenant\Operation;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\OperationTenantUser>
 */
class OperationTenantUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = TenantUser::inRandomOrder()->first();

        $operation = Operation::active()->where('tenant_id', $user->tenant_id)->inRandomOrder()->first();

        return [
            'status' => fake()->randomElement(Status::cases()),
            'tuser_id' => $user->id,
            'operation_id' => $operation->id,
            'tenant_id' => $user->tenant_id,
        ];
    }
}
