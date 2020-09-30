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
                'name' => 'Default Team',
                'owner_id' => $company->managers->first(),
            ])->create();

            Team::factory([
                'name' => 'Default Team',
                'owner_id' => $company->managers->second(),
            ])->create();
        }
    }
}
