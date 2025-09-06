<?php

namespace Database\Seeders;

use App\Models\Tenant\Plant;
use App\Models\Tenant\PlantTenantUser;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PlantTenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plants = Plant::all();

        foreach ($plants as $plant) {
            $users = TenantUser::where('tenant_id', $plant->tenant_id)->inRandomOrder()->limit(rand(1, 2))->get();

            foreach ($users as $user) {
                PlantTenantUser::factory()->state(new Sequence(fn() => [
                    'tenant_user_id' => $user->id,
                    'plant_id' => $plant->id,
                    'tenant_id' => $plant->tenant_id,
                ]))->create();
            }
        }
    }
}
