<?php

namespace Database\Seeders;

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
        $this->call([
            SkillsSeeder::class,
            EmployeeLevelsSeeder::class,
            CoursesSeeder::class
        ]);
    }
}
