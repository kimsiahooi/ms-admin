<?php

namespace Database\Seeders;

use App\Models\Tenant\Bom;
use App\Models\Tenant\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            Bom::factory(20)->create([
                'product_id' => $product->id,
                'tenant_id' => $product->tenant_id,
            ]);
        }
    }
}
