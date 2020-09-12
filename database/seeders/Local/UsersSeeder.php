<?php

namespace Database\Seeders\Local;

use App\Models\Company;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        foreach (Company::all() as $company) {
            Manager::factory(['company_id' => $company])->count(2)->create();
            Employee::factory(['company_id' => $company])->count(4)->create();
        }
    }
}
