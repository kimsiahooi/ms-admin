<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Plant;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\PlantTenantUser>
 */
class PlantTenantUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenant = Tenant::inRandomOrder()->first();

        return [
            'tenant_user_id' => TenantUser::where('tenant_id', $tenant->id)->inRandomOrder()->first(),
            'plant_id' => Plant::where('tenant_id', $tenant->id)->inRandomOrder()->first(),
            'tenant_id' => $tenant->id,
        ];
    }
}
