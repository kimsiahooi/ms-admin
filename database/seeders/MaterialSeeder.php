<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Tenant\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Material::factory(20)->create([
                'tenant_id' => $tenant,
            ]);
        }
    }
}
