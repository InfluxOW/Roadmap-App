<?php

namespace Database\Seeders\Production;

use App\Models\Course;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class CourseTechnologiesSeeder extends Seeder
{
    public function run()
    {
        $data = json_decode(
            file_get_contents(database_path('data/course_technologies.json')),
            true,
            4,
            JSON_THROW_ON_ERROR
        )['course_technologies'];

        foreach ($data as $attributes) {
            $course = Course::whereName($attributes['course'])->firstOrFail();
            $technologies = explode(', ', $attributes['technologies']);

            foreach ($technologies as $technology) {
                $course->technologies()->attach(Technology::whereName($technology)->firstOrFail());
            }
        }
    }
}
