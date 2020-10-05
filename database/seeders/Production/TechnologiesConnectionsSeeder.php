<?php

namespace Database\Seeders\Production;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologiesConnectionsSeeder extends Seeder
{
    public function run()
    {
        $data = json_decode(
            file_get_contents(database_path('data/technologies_connections.json')),
            true,
            4,
            JSON_THROW_ON_ERROR
        )['technologies_connections'];

        foreach ($data as $attributes) {
            $technology = Technology::whereName($attributes['technology'])->firstOrFail();
            $technologies = explode(',', $attributes['related_technologies']);

            foreach ($technologies as $related) {
                $technology->relatedTechnologies()->attach(Technology::whereName($related)->firstOrFail());
            }
        }
    }
}
