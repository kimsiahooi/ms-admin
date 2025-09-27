<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Route;
use App\Models\Tenant\Operation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\OperationRoute>
 */
class OperationRouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $operation = Operation::inRandomOrder()->first();

        $route = Route::active()->where('tenant_id', $operation->tenant_id)->inRandomOrder()->first();

        return [
            'route_id' => $route->id,
            'operation_id' => $operation->id,
            'tenant_id' => $operation->tenant_id,
        ];
    }
}
