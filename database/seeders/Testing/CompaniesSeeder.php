<?php

namespace Database\Seeders\Testing;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    public function run()
    {
        Company::factory()->count(1)->create();
    }
}
