<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\DevelopmentDirection;
use App\Models\Technology;
use App\Models\UserTypes\Employee;
use Tests\TestCase;

class TechnologyTest extends TestCase
{
    /** @test */
    public function it_may_belong_to_many_courses()
    {
        $technology = Technology::factory()->hasAttached(
            Course::factory()->count($count = 3)
        )->create();

        $this->assertTrue($technology->courses->contains(Course::first()));
        $this->assertInstanceOf(Course::class, $technology->courses->first());
        $this->assertCount($count, $technology->courses);
    }

    /** @test */
    public function it_may_belong_to_many_employees()
    {
        $technology = Technology::factory()->hasAttached(
            Employee::factory()->count($count = 3)
        )->create();

        $this->assertTrue($technology->employees->contains(Employee::first()));
        $this->assertInstanceOf(Employee::class, $technology->employees->first());
        $this->assertCount($count, $technology->employees);
    }

    /** @test */
    public function it_may_have_related_technologies()
    {
        $technology = Technology::factory()->has(
            Technology::factory()->count($count = 3),
            'relatedTechnologies'
        )->create();

        $this->assertInstanceOf(Technology::class, $technology->relatedTechnologies->first());
        $this->assertCount($count, $technology->relatedTechnologies);
        $this->assertTrue($technology->hasRelatedTechnologies());
    }

    /** @test */
    public function it_may_be_related_to_technologies()
    {
        $technology = Technology::factory()->has(
            Technology::factory()->count($count = 3),
            'relatedToTechnologies'
        )->create();

        $this->assertInstanceOf(Technology::class, $technology->relatedToTechnologies->first());
        $this->assertCount($count, $technology->relatedToTechnologies);
        $this->assertTrue($technology->hasRelatedToTechnologies());
    }
}
