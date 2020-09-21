<?php

namespace Database\Factories\UserTypes;

use App\Models\User;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return User::factory()->employee()->getRawAttributes(null);
    }
}
