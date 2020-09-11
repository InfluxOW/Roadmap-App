<?php

namespace Database\Seeders\Local;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Technology::factory()->count(20)->create();
    }
}
