<?php

namespace Database\Seeders;

use App\Models\DevelopmentDirection;
use Illuminate\Database\Seeder;

class DevelopmentDirectionsSeeder extends Seeder
{
    public const DIRECTIONS = ['Frontend', 'Backend'];

    public function run()
    {
        foreach (self::DIRECTIONS as $direction) {
            DevelopmentDirection::factory(['name' => $direction])->create();
        }
    }
}
