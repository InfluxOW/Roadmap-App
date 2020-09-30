<?php

namespace Database\Seeders\Local;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamMembersSeeder extends Seeder
{
    public function run()
    {
        foreach (Team::all() as $team) {
            $employees = $team->owner->company->employees()->whereDoesntHave('teams')->inRandomOrder()->take(2)->get();
            $team->employees()->attach($employees, ['assigned_at' => now()]);
        }
    }
}
