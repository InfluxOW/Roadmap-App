<?php

namespace Database\Seeders\Local;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    public function run()
    {
        Company::factory()->count(5)->create();
    }
}
