<?php

namespace Database\Seeders\Local;

use App\Models\Course;
use App\Models\EmployeeLevel;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $levels = EmployeeLevel::all();

        foreach ($levels as $level) {
            Course::factory(['employee_level_id' => $level])->count(20)->create();
        }
    }
}
