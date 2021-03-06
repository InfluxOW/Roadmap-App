<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'owner_id' => Manager::factory(),
        ];
    }
}
