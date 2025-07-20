<?php

namespace Database\Seeders;

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
        Product::factory(100)->withCurrencies(2)
            ->has(Bom::factory()->count(2)
                ->hasAttached(
                    Material::factory()->count(100),
                    function () {
                        return ['quantity' => fake()->numberBetween(100, 1000)];
                    }
                ))->create();
    }
}
