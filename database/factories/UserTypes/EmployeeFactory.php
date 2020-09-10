<?php

namespace Database\Factories\UserTypes;

use App\Models\Model;
use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
