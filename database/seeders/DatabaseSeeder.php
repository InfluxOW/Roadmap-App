<?php

namespace Database\Seeders;

use Database\Seeders\Local;
use Database\Seeders\Testing;
use Database\Seeders\Production;
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
                    Production\SkillsSeeder::class,
                    Production\EmployeeLevelsSeeder::class,
                    Production\CoursesSeeder::class
                ]);
                break;
            case 'local':
                $this->call([
                    Local\EmployeeLevelsSeeder::class,
                    Local\DevelopmentDirectionsSeeder::class,
                    Local\CompaniesSeeder::class,
                    Local\UsersSeeder::class,
                    Local\TeamsSeeder::class,
                    Local\TechnologiesSeeder::class,
                    Local\CoursesSeeder::class,
                    Local\PresetsSeeder::class,
                    Local\PresetCoursesSeeder::class,
                    Local\TeamMembersSeeder::class,
                    Local\EmployeeRoadmapsSeeder::class,
                    Local\CourseTechnologiesSeeder::class,
                    Local\TechnologyDevelopmentDirectionsSeeder::class,
                    Local\EmployeeDevelopmentDirectionsSeeder::class,
                    Local\EmployeeTechnologiesSeeder::class,
                    Local\CourseCompletionsSeeder::class
                ]);
                break;
            case 'testing':
                $this->call([
                    Testing\EmployeeLevelsSeeder::class,
                    Testing\DevelopmentDirectionsSeeder::class,
                    Testing\CompaniesSeeder::class,
                    Testing\UsersSeeder::class,
                    Testing\TeamsSeeder::class,
                    Testing\TechnologiesSeeder::class,
                    Testing\CoursesSeeder::class,
                    Testing\PresetsSeeder::class,
                    Testing\PresetCoursesSeeder::class,
                    Testing\TeamMembersSeeder::class,
                    Testing\EmployeeRoadmapsSeeder::class,
                    Testing\CourseTechnologiesSeeder::class,
                    Testing\TechnologyDevelopmentDirectionsSeeder::class,
                    Testing\EmployeeDevelopmentDirectionsSeeder::class,
                    Testing\EmployeeTechnologiesSeeder::class,
                    Testing\CourseCompletionsSeeder::class
                ]);
                break;
        }
    }
}
