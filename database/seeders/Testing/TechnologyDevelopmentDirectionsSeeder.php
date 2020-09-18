<?php

namespace Database\Seeders\Testing;

use App\Models\DevelopmentDirection;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologyDevelopmentDirectionsSeeder extends Seeder
{
    public function run()
    {
        foreach (Technology::all() as $technology) {
            $directions = DevelopmentDirection::inRandomOrder()->take(random_int(1, 2))->get();
            $technology->directions()->attach($directions);
        }
    }
}
