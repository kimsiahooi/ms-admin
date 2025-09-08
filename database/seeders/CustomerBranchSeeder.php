<?php

namespace Database\Seeders;

use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CustomerBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            CustomerBranch::factory(20)->state(new Sequence(
                fn() => [
                    'customer_id' => $customer->id,
                    'tenant_id' => $customer->tenant_id,
                ]
            ))->create();
        }
    }
}
