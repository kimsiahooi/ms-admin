<?php

namespace Database\Seeders\tenant;

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
        Material::factory(100)->create();
    }
}
