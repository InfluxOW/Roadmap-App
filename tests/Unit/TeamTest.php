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
            Manager::factory(),
            'owner'
        )->create();

        $this->assertEquals(Manager::first(), $team->owner);
        $this->assertTrue($team->owner->is(Manager::first()));
    }

    /** @test */
    public function it_may_have_employees_as_team_members()
    {
        $team = Team::factory()
            ->hasAttached(
                Employee::factory()->count($count = 3),
                ['assigned_at' => now()]
            )
            ->create();

        $this->assertTrue($team->employees->contains(Employee::first()));
        $this->assertInstanceOf(Employee::class, $team->employees->first());
        $this->assertCount($count, $team->employees);
    }
}
