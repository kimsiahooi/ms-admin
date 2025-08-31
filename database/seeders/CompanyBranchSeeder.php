<?php

namespace Database\Seeders;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CompanyBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            CompanyBranch::factory(20)->state(new Sequence(
                [
                    'company_id' => $company->id,
                    'tenant_id' => $company->tenant_id,
                ]
            ))->create();
        }
    }
}
