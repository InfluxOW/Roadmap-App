<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseCompletionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseCompletion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => User::factory()->employee(),
            'course_id' => Course::factory(),
            'rating' => $this->faker->numberBetween(0, 10),
            'completed_at' => $this->faker->dateTimeBetween('-1 year'),
            'certificate' => $this->faker->imageUrl(),
        ];
    }
}
