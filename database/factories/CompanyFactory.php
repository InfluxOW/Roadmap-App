<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'website' => $this->faker->unique()->url,
            'name' => $this->faker->unique()->company,
            'description' => $this->faker->paragraph,
            'foundation_year' => $this->faker->numberBetween(1950, 2020),
            'industry' => $this->faker->words(3, true),
            'location' => $this->faker->address
        ];
    }
}
