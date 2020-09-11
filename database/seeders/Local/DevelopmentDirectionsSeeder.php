<?php

namespace Database\Seeders\Local;

use App\Models\DevelopmentDirection;
use Illuminate\Database\Seeder;

class DevelopmentDirectionsSeeder extends Seeder
{
    public const DIRECTIONS = ['Backend', 'Frontend'];

    public function run()
    {
        foreach (self::DIRECTIONS as $direction) {
            DevelopmentDirection::factory(['name' => $direction])->create();
        }
    }
}
