<?php

namespace Database\Factories;

use App\Models\DevelopmentDirection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class DevelopmentDirectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DevelopmentDirection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Arr::random([
                'Web Development', 'IoT', 'DevOps', 'Mobile Development', 'Game Development'
            ]),
        ];
    }
}