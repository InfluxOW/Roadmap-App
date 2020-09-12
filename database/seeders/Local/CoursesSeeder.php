<?php

namespace Database\Seeders\Local;

use App\Models\Course;
use App\Models\EmployeeLevel;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        foreach (EmployeeLevel::all() as $level) {
            Course::factory(['employee_level_id' => $level])->count(15)->create();

            foreach (Manager::all() as $manager) {
                Course::factory(['employee_level_id' => $level, 'manager_id' => $manager])->count(3)->create();
            }
        }
    }
}
