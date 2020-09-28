<?php

namespace Database\Seeders\Production;

use App\Models\TechnologyForDevelopmentDirection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class TechnologiesConnectionsSeeder extends Seeder
{
    public function run()
    {
        $data = json_decode(
            file_get_contents(database_path('data/technologies_connections.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        )['technologies_connections'];

        foreach ($data as $attributes) {
            $technology = TechnologyForDevelopmentDirection::whereHas('technology', function (Builder $query) use ($attributes) {
                return $query->whereName($attributes['technology']);
            })->whereHas('direction', function (Builder $query) use ($attributes) {
                return $query->whereName($attributes['development_direction']);
            })->first();

            if (is_null($technology)) {
                dd($attributes);
            }

            foreach ($attributes['related_technologies'] as $related) {
                foreach (explode(', ', $related['development_directions']) as $direction) {
                    $technology->relatives()->attach(
                        TechnologyForDevelopmentDirection::whereHas('technology', function (Builder $query) use ($related) {
                            return $query->whereName($related['technology']);
                        })->whereHas('direction', function (Builder $query) use ($direction) {
                            return $query->whereName($direction);
                        })->first()
                    );
                }
            }
        }
    }
}
