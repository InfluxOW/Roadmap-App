<?php

namespace Database\Seeders\Production;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologiesSeeder extends Seeder
{
    public function run()
    {
        $technologies = json_decode(
            file_get_contents(database_path('data/technologies.json')),
            true,
            4,
            JSON_THROW_ON_ERROR
        )['technologies'];

        foreach ($technologies as $attributes) {
            $technology = Technology::make($attributes);

            if ($attributes['name'] === 'C#') {
                $technology->slug = "c_sharp";
            }

            if ($attributes['name'] === 'C++') {
                $technology->slug = "c_plus_plus";
            }

            $technology->save();
        }
    }
}
