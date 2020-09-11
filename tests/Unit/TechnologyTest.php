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
    public function it_may_belong_to_many_directions()
    {
        $technology = Technology::factory()->hasAttached(
            DevelopmentDirection::factory()->count($count = 3),
            [],
            'directions'
        )->create();

        $this->assertTrue($technology->directions->contains(DevelopmentDirection::first()));
        $this->assertInstanceOf(DevelopmentDirection::class, $technology->directions->first());
        $this->assertCount($count, $technology->directions);
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
}
