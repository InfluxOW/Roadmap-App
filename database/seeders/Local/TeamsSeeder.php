<?php

namespace Database\Seeders\Local;

use App\Models\Company;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    public function run()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            Team::factory([
                'owner_id' => $company->managers->first(),
                'company_id' => $company
            ])->count(2)->create();
        }
    }
}
