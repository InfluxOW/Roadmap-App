<?php

namespace Database\Factories\UserTypes;

use App\Models\User;
use App\Models\UserTypes\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return User::factory()->admin()->getRawAttributes(null);
    }
}
