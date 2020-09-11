<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $courses = json_decode(
            file_get_contents(database_path('data/courses.json')),
            true,
            4,
            JSON_THROW_ON_ERROR
        )['courses'];

        foreach ($courses as $attributes) {
            if (Course::whereName($attributes['name'])->orWhere('source', $attributes['source'])->exists()) {
                continue;
            }

            $course = Course::make([
                'name' => $attributes['name'],
                'description' => $attributes['description'],
                'source' => $attributes['source']
            ]);


            $course->level()->associate($attributes['employee_level_id']);
            $course->save();

            $course->technologies()->attach(Technology::whereName($attributes['technology'])->first());
        }
    }
}
