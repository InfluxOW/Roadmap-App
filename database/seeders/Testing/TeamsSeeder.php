<?php

namespace Database\Seeders\Testing;

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
            ])->create();

            Team::factory([
                'owner_id' => $company->managers->second(),
            ])->create();
        }
    }
}
