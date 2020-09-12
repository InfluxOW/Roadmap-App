<?php

namespace Database\Seeders\Local;

use App\Models\Preset;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;

class PresetsSeeder extends Seeder
{
    public function run()
    {
        Preset::factory(['manager_id' => null])->count(10)->create();

        foreach (Manager::all() as $manager) {
            Preset::factory(['manager_id' => $manager])->create();
        }
    }
}
