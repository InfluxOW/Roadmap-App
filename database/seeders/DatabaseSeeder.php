<?php

namespace Database\Seeders;

use Database\Seeders\Local;
use Database\Seeders\Production;
use Database\Seeders\Testing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        switch (app('env')) {
            case 'production':
                $this->call([
                    EmployeeLevelsSeeder::class,
                    DevelopmentDirectionsSeeder::class,
                    Local\CompaniesSeeder::class,
                    Local\UsersSeeder::class,
                    Local\TeamsSeeder::class,
                    Local\TeamMembersSeeder::class,
                    Production\TechnologiesSeeder::class,
                    Production\CoursesSeeder::class,
                    Production\CourseTechnologiesSeeder::class,
                    Production\PresetsSeeder::class,
                    Local\EmployeeRoadmapsSeeder::class,
                    Local\EmployeeDevelopmentDirectionsSeeder::class,
                    Local\EmployeeTechnologiesSeeder::class,
                    Local\CourseCompletionsSeeder::class,
                ]);
                break;
            case 'local':
                $this->call([
                    EmployeeLevelsSeeder::class,
                    DevelopmentDirectionsSeeder::class,
                    Local\CompaniesSeeder::class,
                    Local\UsersSeeder::class,
                    Local\TeamsSeeder::class,
                    Local\TeamMembersSeeder::class,
                    Local\TechnologiesSeeder::class,
                    Local\CoursesSeeder::class,
                    Local\CourseTechnologiesSeeder::class,
                    Local\PresetsSeeder::class,
                    Local\PresetCoursesSeeder::class,
                    Local\EmployeeRoadmapsSeeder::class,
                    Local\EmployeeDevelopmentDirectionsSeeder::class,
                    Local\EmployeeTechnologiesSeeder::class,
                    Local\CourseCompletionsSeeder::class
                ]);
                break;
            case 'testing':
                $this->call([
                    EmployeeLevelsSeeder::class,
                    DevelopmentDirectionsSeeder::class,
                    Testing\CompaniesSeeder::class,
                    Testing\UsersSeeder::class,
                    Testing\TeamsSeeder::class,
                    Testing\TeamMembersSeeder::class,
                    Testing\TechnologiesSeeder::class,
                    Testing\CoursesSeeder::class,
                    Testing\CourseTechnologiesSeeder::class,
                    Testing\PresetsSeeder::class,
                    Testing\PresetCoursesSeeder::class,
                    Testing\EmployeeRoadmapsSeeder::class,
                    Testing\EmployeeDevelopmentDirectionsSeeder::class,
                    Testing\EmployeeTechnologiesSeeder::class,
                    Testing\CourseCompletionsSeeder::class
                ]);
                break;
        }
    }
}
