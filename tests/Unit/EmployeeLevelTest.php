<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\EmployeeLevel;
use Tests\TestCase;

class EmployeeLevelTest extends TestCase
{
    /** @test */
    public function it_may_have_courses()
    {
        $level = EmployeeLevel::factory()->has(
            Course::factory()->count($count = 3)
        )->create();

        $this->assertTrue($level->courses->contains(Course::first()));
        $this->assertInstanceOf(Course::class, $level->courses->first());
        $this->assertCount($count, $level->courses);
    }
}
