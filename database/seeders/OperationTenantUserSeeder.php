<?php

namespace Database\Seeders;

use App\Models\Tenant\Operation;
use App\Models\Tenant\OperationTenantUser;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class OperationTenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = Operation::all();

        foreach ($operations as $operation) {
            $users = TenantUser::where('tenant_id', $operation->tenant_id)->inRandomOrder()->limit(rand(0, 2))->get();

            foreach ($users as $user) {
                OperationTenantUser::factory()->state(new Sequence(fn() => [
                    'tuser_id' => $user->id,
                    'operation_id' => $operation->id,
                    'tenant_id' => $user->tenant_id,
                ]))->create();
            }
        }
    }
}
