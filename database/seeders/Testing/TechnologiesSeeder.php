<?php

namespace Database\Seeders\Testing;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologiesSeeder extends Seeder
{
    public function run()
    {
        Technology::factory()->count(10)->create();
    }
}
