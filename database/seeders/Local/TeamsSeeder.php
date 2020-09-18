<?php

namespace Database\Seeders\Local;

use App\Models\Company;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    public function run()
    {
        foreach (Company::all() as $company) {
            Team::factory([
                'owner_id' => $company->managers->first(),
                'company_id' => $company
            ])->create();

            Team::factory([
                'owner_id' => $company->managers->second(),
                'company_id' => $company
            ])->create();
        }
    }
}
