<?php

namespace Database\Seeders\Local;

use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Seeder;

class EmployeeRoadmapsSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $presets = Preset::inRandomOrder()->take(2)->get();
            $manager = $employee->teams->first()->owner;

            Roadmap::factory(['employee_id' => $employee, 'preset_id' => $presets->first(), 'manager_id' => $manager, 'assigned_at' => now()])->create();
            Roadmap::factory(['employee_id' => $employee, 'preset_id' => $presets->second(), 'manager_id' => $manager, 'assigned_at' => now()])->create();
        }
    }
}
