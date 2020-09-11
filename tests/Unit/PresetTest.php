<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class PresetTest extends TestCase
{
    /** @test */
    public function it_may_belong_to_a_manager()
    {
        $preset = Preset::factory()->for(Manager::factory())->create();

        $this->assertEquals(Manager::first(), $preset->manager);
        $this->assertTrue($preset->manager->is(Manager::first()));
    }

    /** @test */
    public function it_may_have_many_roadmaps()
    {
        $preset = Preset::factory()->has(Roadmap::factory()->count($count = 3))->create();

        $this->assertTrue($preset->roadmaps->contains(Roadmap::first()));
        $this->assertInstanceOf(Roadmap::class, $preset->roadmaps->first());
        $this->assertCount($count, $preset->roadmaps);
    }

    /** @test */
    public function it_may_belong_to_many_courses()
    {
        $preset = Preset::factory()->hasAttached(
            Course::factory()->count($count = 3),
            ['assigned_at' => now()]
        )->create();

        $this->assertTrue($preset->courses->contains(Course::first()));
        $this->assertInstanceOf(Course::class, $preset->courses->first());
        $this->assertCount($count, $preset->courses);
    }
}
