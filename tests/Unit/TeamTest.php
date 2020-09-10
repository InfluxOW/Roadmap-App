<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Team;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_manager()
    {
        $team = Team::factory()->for(
            Manager::factory(), 'owner'
        )->create();

        $this->assertEquals(Manager::first(), $team->owner);
        $this->assertTrue($team->owner->is(Manager::first()));
    }

    /** @test */
    public function it_belongs_to_a_company()
    {
        $team = Team::factory()->for(Company::factory())->for(
            Manager::factory(['company_id' => Company::first()]), 'owner'
        )
            ->create();

        $this->assertEquals(Company::first(), $team->company);
        $this->assertTrue($team->company->is(Company::first()));
    }

    /** @test */
    public function it_has_managers_as_team_members()
    {
        $team = Team::factory()
            ->hasAttached(
                Manager::factory()->count($count = 3), ['assigned_at' => now()]
            )
            ->create();

        /* Second because first is a Manager who owns the Team */
        $this->assertTrue($team->managers->contains(Manager::find(2)));
        $this->assertInstanceOf(Manager::class, $team->managers->second());
        $this->assertCount($count, $team->managers);
    }

    /** @test */
    public function it_has_employees_as_team_members()
    {
        $team = Team::factory()
            ->hasAttached(
                Employee::factory()->count($count = 3), ['assigned_at' => now()]
            )
            ->create();

        $this->assertTrue($team->employees->contains(Employee::first()));
        $this->assertInstanceOf(Employee::class, $team->employees->first());
        $this->assertCount($count, $team->employees);
    }
}
