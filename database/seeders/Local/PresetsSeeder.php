<?php

namespace Database\Seeders\Local;

use App\Models\Preset;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;

class PresetsSeeder extends Seeder
{
    public function run()
    {
        $managers = Manager::all();

        Preset::factory(['manager_id' => null])->count(10)->create();

        foreach ($managers as $manager) {
            Preset::factory(['manager_id' => $manager])->count(2)->create();
        }
    }
}
