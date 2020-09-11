<?php

namespace Database\Seeders\Local;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamMembersSeeder extends Seeder
{
    public function run()
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            $employees = $team->company->employees()->whereDoesntHave('teams')->inRandomOrder()->take(2)->get();
            $team->employees()->attach($employees, ['assigned_at' => now()]);
        }
    }
}
