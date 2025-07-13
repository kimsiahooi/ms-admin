<?php

namespace Database\Seeders\Tenant;

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
        Product::factory(100)->withMaterials(2)->create();
    }
}
