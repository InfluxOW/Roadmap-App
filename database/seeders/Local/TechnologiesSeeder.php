<?php

namespace Database\Seeders\Local;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologiesSeeder extends Seeder
{
    public function run()
    {
        Technology::factory()->count(20)->create();
    }
}
