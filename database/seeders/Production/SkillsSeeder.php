<?php

namespace Database\Seeders\Production;

use App\Models\DevelopmentDirection;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    public function run()
    {
        $skills = json_decode(
            file_get_contents(database_path('data/skills.json')),
            true,
            3,
            JSON_THROW_ON_ERROR
        );

        foreach ($skills as $direction => $technologies) {
            $direction = DevelopmentDirection::create(['name' => $direction]);

            foreach ($technologies as $technology) {
                $technology = Technology::firstOrCreate(['name' => $technology, 'description' => 'No Description...']);
                $technology->directions()->attach($direction);
            }
        }
    }
}
