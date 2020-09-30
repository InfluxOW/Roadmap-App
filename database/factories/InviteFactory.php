<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Invite;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class InviteFactory extends Factory
{
    protected $model = Invite::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->email,
            'role' => $this->faker->randomElement(['employee', 'manager']),
            'code' => $this->faker->uuid,
            'company_id' => Company::factory(),
            'expires_at' => now()->addDay(),
            'sent_by_id' => Manager::factory(),
        ];
    }
}
