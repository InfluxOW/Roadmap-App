<?php

namespace Database\Seeders\Production;

use App\Models\DevelopmentDirection;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologyDevelopmentDirectionsSeeder extends Seeder
{
    public function run()
    {
        $data = json_decode(
            file_get_contents(database_path('data/technology_development_directions.json')),
            true,
            4,
            JSON_THROW_ON_ERROR
        )['technology_development_directions'];

        foreach ($data as $attributes) {
            $technology = Technology::whereName($attributes['technology'])->firstOrFail();
            $directions = explode(', ', $attributes['development_directions']);

            foreach ($directions as $direction) {
                $technology->directions()->attach(DevelopmentDirection::whereName($direction)->firstOrFail());
            }
        }
    }
}
