<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\Team;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company()
    {
        $manager = Manager::factory()->for(Company::factory())->create();

        $this->assertEquals(Company::first(), $manager->company);
        $this->assertTrue($manager->company->is(Company::first()));
    }

    /** @test */
    public function it_may_own_many_teams()
    {
        $manager = Manager::factory()->has(
            Team::factory()->count($count = 3)
        )->create();

        $this->assertTrue($manager->teams->contains(Team::first()));
        $this->assertInstanceOf(Team::class, $manager->teams->first());
        $this->assertCount($count, $manager->teams);
    }

    /** @test */
    public function it_hay_have_many_presets()
    {
        $manager = Manager::factory()->has(
            Preset::factory()->count($count = 3)
        )->create();

        $this->assertTrue($manager->presets->contains(Preset::first()));
        $this->assertInstanceOf(Preset::class, $manager->presets->first());
        $this->assertCount($count, $manager->presets);
    }

    /** @test */
    public function it_may_have_many_roadmaps()
    {
        $manager = Manager::factory()->has(
            Roadmap::factory()->count($count = 3)
        )->create();

        $this->assertTrue($manager->roadmaps->contains(Roadmap::first()));
        $this->assertInstanceOf(Roadmap::class, $manager->roadmaps->first());
        $this->assertCount($count, $manager->roadmaps);
    }

    /** @test */
    public function it_knows_all_its_employees()
    {
        $manager = Manager::factory()
            ->has(
                Team::factory()->hasAttached(
                    Employee::factory()->count(3),
                    ['assigned_at' => now()]
                )
            )
            ->create();

        $this->assertCount(
            $manager->teams->first()->employees->count(),
            $manager->employees
        );
    }
}
