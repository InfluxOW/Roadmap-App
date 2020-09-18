<?php

namespace Database\Seeders\Testing;

use App\Models\Course;
use App\Models\Preset;
use Illuminate\Database\Seeder;

class PresetCoursesSeeder extends Seeder
{
    public function run()
    {
        foreach (Preset::all() as $preset) {
            $courses = Course::inRandomOrder()->take(5)->get();
            $preset->courses()->attach($courses, ['assigned_at' => now()]);
        }
    }
}
