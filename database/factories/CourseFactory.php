<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\EmployeeLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraph,
            'source' => $this->faker->unique()->url,
            'employee_level_id' => EmployeeLevel::factory(),
        ];
    }
}
