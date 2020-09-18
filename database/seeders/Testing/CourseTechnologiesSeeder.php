<?php

namespace Database\Seeders\Testing;

use App\Models\Course;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class CourseTechnologiesSeeder extends Seeder
{
    public function run()
    {
        foreach (Course::all() as $course) {
            $technologies = Technology::inRandomOrder()->take(random_int(1, 3))->get();
            $course->technologies()->attach($technologies);
        }
    }
}
