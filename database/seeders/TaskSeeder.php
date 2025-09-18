<?php

namespace Database\Seeders;

use App\Models\Tenant\Operation;
use App\Models\Tenant\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = Operation::all();

        foreach ($operations as $operation) {
            Task::factory(2)->create([
                'operation_id' => $operation->id,
                'tenant_id' => $operation->tenant_id,
            ]);
        }
    }
}
