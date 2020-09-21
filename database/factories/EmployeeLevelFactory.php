<?php

namespace Database\Factories;

use App\Models\EmployeeLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeLevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
