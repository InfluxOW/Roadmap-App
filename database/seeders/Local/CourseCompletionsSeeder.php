<?php

namespace Database\Seeders\Local;

use App\Models\CourseCompletion;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Seeder;

class CourseCompletionsSeeder extends Seeder
{
    public function run()
    {
        foreach (Employee::all() as $employee) {
            $courses = $employee->roadmaps->map(function ($roadmap) {
               return $roadmap->preset->courses;
            })->flatten()->unique('id', true);
            $take = $courses->random(random_int(1, $courses->count() - 6));

            foreach ($take as $course) {
                CourseCompletion::factory(['employee_id' => $employee, 'course_id' => $course, 'completed_at' => now()])->create();
            }
        }
    }
}
