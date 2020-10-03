<?php

namespace Database\Seeders\Local;

use App\Models\Team;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    public function run()
    {
        foreach (Manager::all() as $manager) {
            Team::factory([
                'name' => 'Default Team',
                'owner_id' => $manager,
            ])->create();
        }
    }
}
