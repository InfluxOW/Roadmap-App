<?php

namespace Database\Seeders\Production;

use App\Models\Preset;
use App\Models\Technology;
use App\Models\UserTypes\Manager;
use Facades\App\Repositories\PresetsGenerationRepository;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class GeneratedPresetsSeeder extends Seeder
{
    public function run()
    {
        Preset::factory(['manager_id' => null])->count(10)->create();

        foreach (Manager::all() as $manager) {
            Preset::factory(['manager_id' => $manager])->create();
        }

        foreach (Preset::all() as $preset) {
            $request = new Request();
            $request->technologies = Technology::inRandomOrder()->take(5)->pluck('name')->toArray();

            PresetsGenerationRepository::store($request, $preset);
        }
    }
}
