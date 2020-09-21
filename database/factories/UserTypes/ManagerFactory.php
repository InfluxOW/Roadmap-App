<?php

namespace Database\Factories\UserTypes;

use App\Models\User;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return User::factory()->manager()->getRawAttributes(null);
    }
}
