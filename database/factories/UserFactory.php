<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name($sex = Arr::random(['male', 'female'])),
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'company_id' => Company::factory(),
            'sex' => $sex,
            'age' => $this->faker->numberBetween(18, 60),
            'position' => $this->faker->jobTitle,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state([
           'type' => 'admin'
        ]);
    }

    public function manager()
    {
        return $this->state([
            'type' => 'manager'
        ]);
    }

    public function employee()
    {
        return $this->state([
            'type' => 'employee'
        ]);
    }
}
