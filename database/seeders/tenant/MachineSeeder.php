<?php

namespace Database\Seeders\tenant;

use App\Models\Tenant\Machine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Machine::factory(100)->create();
    }
}
