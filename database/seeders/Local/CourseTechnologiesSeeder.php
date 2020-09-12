<?php

namespace Database\Seeders\Local;

use App\Models\Course;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class CourseTechnologiesSeeder extends Seeder
{
    public function run()
    {
        foreach (Technology::all() as $technology) {
            $courses = Course::inRandomOrder()->take(random_int(1, 3))->get();
            $technology->courses()->attach($courses);
        }
    }
}
