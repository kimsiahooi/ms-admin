<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Tenant\Bom;
use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Product::factory(20)->create([
                'tenant_id' => $tenant,
            ]);
        }
    }
}
