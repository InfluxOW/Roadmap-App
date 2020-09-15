<?php

namespace Database\Seeders;

use Database\Seeders\Local\CompaniesSeeder;
use Database\Seeders\Local\CourseCompletionsSeeder;
use Database\Seeders\Local\CoursesSeeder;
use Database\Seeders\Local\CourseTechnologiesSeeder;
use Database\Seeders\Local\DevelopmentDirectionsSeeder;
use Database\Seeders\Local\EmployeeDevelopmentDirectionsSeeder;
use Database\Seeders\Local\EmployeeLevelsSeeder;
use Database\Seeders\Local\EmployeeRoadmapsSeeder;
use Database\Seeders\Local\EmployeeTechnologiesSeeder;
use Database\Seeders\Local\PresetCoursesSeeder;
use Database\Seeders\Local\PresetsSeeder;
use Database\Seeders\Local\TeamMembersSeeder;
use Database\Seeders\Local\TeamsSeeder;
use Database\Seeders\Local\TechnologiesSeeder;
use Database\Seeders\Local\TechnologyDevelopmentDirectionsSeeder;
use Database\Seeders\Local\UsersSeeder;
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
//            case 'production':
//                $this->call([
//                    SkillsSeeder::class,
//                    EmployeeLevelsSeeder::class,
//                    CoursesSeeder::class
//                ]);
//                break;
            default:
                $this->call([
                    EmployeeLevelsSeeder::class,
                    DevelopmentDirectionsSeeder::class,
                    CompaniesSeeder::class,
                    UsersSeeder::class,
                    TeamsSeeder::class,
                    TechnologiesSeeder::class,
                    CoursesSeeder::class,
                    PresetsSeeder::class,
                    PresetCoursesSeeder::class,
                    TeamMembersSeeder::class,
                    EmployeeRoadmapsSeeder::class,
                    CourseTechnologiesSeeder::class,
                    TechnologyDevelopmentDirectionsSeeder::class,
                    EmployeeDevelopmentDirectionsSeeder::class,
                    EmployeeTechnologiesSeeder::class,
                    CourseCompletionsSeeder::class
                ]);
                break;
        }
    }
}
