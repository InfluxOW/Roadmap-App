<?php

namespace Database\Seeders\Testing;

use App\Models\CourseCompletion;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Seeder;

class CourseCompletionsSeeder extends Seeder
{
    public function run()
    {
        foreach (Employee::all() as $employee) {
            $take = $employee->courses->random(random_int(1, round($employee->courses->count() / 2, 0)));

            foreach ($take as $course) {
                CourseCompletion::factory(['employee_id' => $employee, 'course_id' => $course, 'completed_at' => now()])->create();
            }
        }
    }
}
