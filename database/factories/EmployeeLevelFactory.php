<?php

namespace Database\Factories;

use App\Models\EmployeeLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
            'name' => Arr::random([
                'Trainee', 'Junior', 'Middle', 'Senior'
            ]),
        ];
    }
}
