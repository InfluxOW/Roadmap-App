<?php

namespace Database\Seeders\Production;

use App\Models\Preset;
use App\Models\Technology;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class PresetsSeeder extends Seeder
{
    public function run()
    {
        Preset::factory(['manager_id' => null])->count(10)->create();

        foreach (Manager::all() as $manager) {
            Preset::factory(['manager_id' => $manager])->create();
        }

        foreach (Preset::all() as $preset) {
            $technologies = Technology::inRandomOrder()->take(10)->get();
            $courses = $technologies->map(function ($technology) {
                return $technology->courses;
            })->flatten()->unique('id', true);

            $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);
            $preset->save();
        }
    }
}
