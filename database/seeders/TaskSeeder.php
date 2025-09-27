<?php

namespace Database\Seeders;

use App\Models\Tenant\Department;
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
        $departments = Department::all();

        foreach ($departments as $department) {
            Task::factory(2)->create([
                'department_id' => $department->id,
                'tenant_id' => $department->tenant_id,
            ]);
        }
    }
}
