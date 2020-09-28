<?php

namespace Database\Seeders\Production;

use App\Models\Preset;
use App\Models\Technology;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;

class PresetsSeeder extends Seeder
{
//    public function run()
//    {
//        Preset::factory(['manager_id' => null])->count(10)->create();
//
//        foreach (Manager::all() as $manager) {
//            Preset::factory(['manager_id' => $manager])->create();
//        }
//
//        foreach (Preset::all() as $preset) {
//            $input = TechnologyForDevelopmentDirection::inRandomOrder()->take(3)->get();
//            $technologies = $this->getTechnologies($input);
//            $courses = $this->getCourses($technologies);
//
//            $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);
//        }
//    }
//
//    private function getTechnologies($technologies)
//    {
//        return $technologies->map(function ($technology) {
//            if ($technology->hasRelatives()) {
//                return $technology->relatives->map(function ($technology) {
//                    return $technology->technology;
//                })->add($technology->technology);
//            }
//
//            return $technology->technology;
//        })->flatten()->unique('id', true);
//    }
//
//    private function getCourses($technologies)
//    {
//        return $technologies->map(function ($technology) {
//            return $technology->courses;
//        })->flatten()->unique('id', true);
//    }

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
