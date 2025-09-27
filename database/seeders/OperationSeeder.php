<?php

namespace Database\Seeders;

use App\Models\Tenant\Department;
use App\Models\Tenant\Operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        foreach ($departments as $department) {
            Operation::factory(2)->create([
                'department_id' => $department->id,
                'tenant_id' => $department->tenant_id,
            ]);
        }
    }
}
