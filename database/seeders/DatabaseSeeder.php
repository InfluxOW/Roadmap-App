<?php

namespace Database\Seeders;

use Database\Seeders\Local\CompaniesSeeder;
use Database\Seeders\Local\CoursesSeeder;
use Database\Seeders\Local\DevelopmentDirectionsSeeder;
use Database\Seeders\Local\EmployeeLevelsSeeder;
use Database\Seeders\Local\EmployeeRoadmapsSeeder;
use Database\Seeders\Local\PresetCoursesSeeder;
use Database\Seeders\Local\PresetsSeeder;
use Database\Seeders\Local\TeamMembersSeeder;
use Database\Seeders\Local\TeamsSeeder;
use Database\Seeders\Local\TechnologiesSeeder;
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
        if (app('env') === 'local') {
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
            ]);
        }

        if (app('env') === 'production') {
            $this->call([
                SkillsSeeder::class,
                EmployeeLevelsSeeder::class,
                CoursesSeeder::class
            ]);
        }
    }
}
