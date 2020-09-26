<?php

namespace Database\Seeders;

use App\Models\EmployeeLevel;
use Illuminate\Database\Seeder;

class EmployeeLevelsSeeder extends Seeder
{
    public const LEVELS = ['Junior', 'Middle', 'Senior'];

    public function run()
    {
        foreach (self::LEVELS as $level) {
            EmployeeLevel::factory(['name' => $level])->create();
        }
    }
}
