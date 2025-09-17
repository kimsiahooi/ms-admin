<?php

namespace Database\Seeders;

use App\Models\Tenant\Product;
use App\Models\Tenant\ProductPreset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductPresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            ProductPreset::factory(20)->state(new Sequence(fn() => [
                'product_id' => $product->id,
                'tenant_id' => $product->tenant_id,
            ]))->create();
        }
    }
}
