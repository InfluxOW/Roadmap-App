<?php

namespace Database\Seeders\Production;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

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

        foreach ($technologies as $technology) {
            Technology::create(Arr::only($technology, ['name', 'description']));
        }
    }
}
