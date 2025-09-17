<?php

namespace Database\Seeders;

use App\Models\Tenant\Operation;
use App\Models\Tenant\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plants = Plant::all();

        foreach ($plants as $plant) {
            Operation::factory(20)->create([
                'plant_id' => $plant->id,
                'tenant_id' => $plant->tenant_id,
            ]);
        }
    }
}
