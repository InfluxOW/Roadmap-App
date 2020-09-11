<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\UserTypes\Employee;
use Tests\TestCase;

class CourseCompletionTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_employee()
    {
        $completion = CourseCompletion::factory()->for(
            Employee::factory()
        )->create();

        $this->assertEquals(Employee::first(), $completion->employee);
        $this->assertTrue($completion->employee->is(Employee::first()));
    }

    /** @test */
    public function it_belongs_to_a_course()
    {
        $completion = CourseCompletion::factory()->for(
            Course::factory()
        )->create();

        $this->assertEquals(Course::first(), $completion->course);
        $this->assertTrue($completion->course->is(Course::first()));
    }
}
