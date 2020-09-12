<?php

namespace Database\Seeders\Local;

use App\Models\DevelopmentDirection;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Seeder;

class EmployeeDevelopmentDirectionsSeeder extends Seeder
{
    public function run()
    {
        foreach (Employee::all() as $employee) {
            $directions = DevelopmentDirection::inRandomOrder()->take(random_int(1, 2))->get();
            $employee->directions()->attach($directions);
        }
    }
}
