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

    /** @test */
    public function a_course_can_be_assigned_to_a_preset()
    {
        $preset = Preset::factory()->create();
        $course = Course::factory()->create();

        $this->assertFalse($preset->hasCourse($course));

        $preset->assignCourse($course);

        $this->assertTrue($preset->fresh()->hasCourse($course));
    }

    /** @test */
    public function a_course_cannot_be_assigned_twice()
    {
        $preset = Preset::factory()->create();
        $course = Course::factory()->create();
        $preset->assignCourse($course);

        $this->expectException(\LogicException::class);

        $preset->fresh()->assignCourse($course);
    }

    /** @test */
    public function a_course_can_be_assigned_from_a_preset()
    {
        $preset = Preset::factory()->hasAttached(
            Course::factory(),
            ['assigned_at' => now()]
        )->create();
        $course = $preset->courses->first();

        $this->assertTrue($preset->hasCourse($course));

        $preset->unassignCourse($course);

        $this->assertFalse($preset->fresh()->hasCourse($course));
    }

    /** @test */
    public function only_a_course_that_exists_in_a_preset_can_be_unassigned_from_it()
    {
        $preset = Preset::factory()->create();
        $course = Course::factory()->create();

        $this->assertFalse($preset->hasCourse($course));
        $this->expectException(\LogicException::class);

        $preset->unassignCourse($course);
    }
}
