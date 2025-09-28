<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Route\Bom\Status;
use App\Models\Tenant\Bom;
use App\Models\Tenant\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\BomRoute>
 */
class BomRouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $route = Route::inRandomOrder()->first();
        $bom = Bom::where('tenant_id', $route->tenant_id)->inRandomOrder()->first();

        return [
            'status' => fake()->randomElement(Status::cases()),
            'route_id' => $route->id,
            'bom_id' => $bom->id,
            'tenant_id' => $route->tenant_id,
        ];
    }
}
