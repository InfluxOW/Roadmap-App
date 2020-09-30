<?php

namespace Database\Factories;

use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoadmapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Roadmap::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'manager_id' => Manager::factory(),
            'preset_id' => Preset::factory(),
            'assigned_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }
}
