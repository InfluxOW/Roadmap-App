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
        $companies = Company::all();

        foreach ($companies as $company) {
            Manager::factory(['company_id' => $company])->create();
            Employee::factory(['company_id' => $company])->count(4)->create();
        }
    }
}
