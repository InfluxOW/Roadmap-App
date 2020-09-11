<?php

namespace Tests\Unit;

use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class RoadmapTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_employee()
    {
        $roadmap = Roadmap::factory()->for(Employee::factory())->create();

        $this->assertEquals(Employee::first(), $roadmap->employee);
        $this->assertTrue($roadmap->employee->is(Employee::first()));
    }

    /** @test */
    public function it_belongs_to_a_preset()
    {
        $roadmap = Roadmap::factory()->for(Preset::factory())->create();

        $this->assertEquals(Preset::first(), $roadmap->preset);
        $this->assertTrue($roadmap->preset->is(Preset::first()));
    }

    /** @test */
    public function it_belongs_to_a_manager()
    {
        $roadmap = Roadmap::factory()->for(Manager::factory())->create();

        $this->assertEquals(Manager::first(), $roadmap->manager);
        $this->assertTrue($roadmap->manager->is(Manager::first()));
    }
}
