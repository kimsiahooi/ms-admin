<?php

namespace Database\Seeders;

use App\Models\Tenant\Bom;
use App\Models\Tenant\BomMaterial;
use App\Models\Tenant\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BomMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boms = Bom::all();

        foreach ($boms as $bom) {
            $materials = Material::active()->where('tenant_id', $bom->tenant_id)->inRandomOrder()->limit(rand(1, 2))->get();

            foreach ($materials as $material) {
                BomMaterial::factory()->state(new Sequence(fn() => [
                    'bom_id' => $bom->id,
                    'material_id' => $material->id,
                    'unit_type' => $material->unit_type,
                    'tenant_id' => $bom->tenant_id,
                ]))->create();
            }
        }
    }
}
