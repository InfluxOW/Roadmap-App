<?php

namespace Database\Seeders\Local;

use App\Models\Technology;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Seeder;

class EmployeeTechnologiesSeeder extends Seeder
{
    public function run()
    {
        foreach (Employee::all() as $employee) {
            $technologies = Technology::inRandomOrder()->take(random_int(2, 4))->get();
            $employee->technologies()->attach($technologies);
        }
    }
}
