<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Bom;
use App\Models\Tenant\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\BomMaterial>
 */
class BomMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bom = Bom::inRandomOrder()->first();

        $material = Material::active()->where('tenant_id', $bom->tenant_id)->inRandomOrder()->first();

        return [
            'bom_id' => $bom->id,
            'material_id' => $material->id,
            'quantity' => fake()->randomFloat(2, 1, 999),
            'unit_type' => $material->unit_type,
            'tenant_id' => $bom->tenant_id,
        ];
    }
}
