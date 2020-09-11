<?php

namespace Database\Seeders;

use App\Models\EmployeeLevel;
use Illuminate\Database\Seeder;

class EmployeeLevelsSeeder extends Seeder
{
    public const LEVELS = ['junior', 'middle', 'senior'];

    public function run()
    {
        foreach (self::LEVELS as $level) {
            EmployeeLevel::create(['name' => $level]);
        }
    }
}
