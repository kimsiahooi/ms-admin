<?php

namespace Database\Seeders;

use App\Enums\Tenant\Product\Price\Currency;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $currencies = Currency::cases();

        foreach ($products as $product) {
            foreach ($currencies as $currency) {
                ProductPrice::factory()->create([
                    'product_id' => $product->id,
                    'tenant_id' => $product->tenant_id,
                    'currency' => $currency->value,
                ]);
            }
        }
    }
}
