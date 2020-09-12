<?php

namespace Database\Seeders\Local;

use App\Models\Course;
use App\Models\EmployeeLevel;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        foreach (EmployeeLevel::all() as $level) {
            Course::factory(['employee_level_id' => $level])->count(20)->create();
        }
    }
}
