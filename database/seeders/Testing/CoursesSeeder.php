<?php

namespace Database\Seeders\Testing;

use App\Models\Course;
use App\Models\EmployeeLevel;
use App\Models\UserTypes\Manager;
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
