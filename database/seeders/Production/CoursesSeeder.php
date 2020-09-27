<?php

namespace Database\Seeders\Production;

use App\Models\Course;
use App\Models\EmployeeLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

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
            $course = Course::make(Arr::only($attributes, ['name', 'source', 'description']));
            $course->level()->associate(EmployeeLevel::whereName($attributes['employee_level'])->first());
            $course->save();
        }
    }
}
