<?php

namespace Database\Factories\UserTypes;

use App\Models\Model;
use App\Models\User;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Manager;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
