<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\EmployeeLevel;
use App\Models\Preset;
use App\Models\Technology;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class CourseTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_employee_level()
    {
        $course = Course::factory()->for(
            EmployeeLevel::factory(),
            'level'
        )->create();

        $this->assertEquals(EmployeeLevel::first(), $course->level);
        $this->assertTrue($course->level->is(EmployeeLevel::first()));
    }

    /** @test */
    public function it_may_have_many_completions()
    {
        $course = Course::factory()->has(
            CourseCompletion::factory()->count($count = 3),
            'completions'
        )->create();

        $this->assertTrue($course->completions->contains(CourseCompletion::first()));
        $this->assertInstanceOf(CourseCompletion::class, $course->completions->first());
        $this->assertCount($count, $course->completions);
    }

    /** @test */
    public function it_may_belong_to_many_technologies()
    {
        $course = Course::factory()->hasAttached(
            Technology::factory()->count($count = 3)
        )->create();

        $this->assertTrue($course->technologies->contains(Technology::first()));
        $this->assertInstanceOf(Technology::class, $course->technologies->first());
        $this->assertCount($count, $course->technologies);
    }

    /** @test */
    public function it_may_belong_to_many_presets()
    {
        $course = Course::factory()->hasAttached(
            Preset::factory()->count($count = 3),
            ['assigned_at' => now()]
        )->create();

        $this->assertTrue($course->presets->contains(Preset::first()));
        $this->assertInstanceOf(Preset::class, $course->presets->first());
        $this->assertCount($count, $course->presets);
    }

    /** @test */
    public function it_knows_if_it_has_been_completed_or_incompleted_by_the_specific_employee()
    {
        $course = Course::factory()->create();
        $employee = Employee::factory()->create();

        $this->assertFalse($course->isCompletedBy($employee));
        $this->assertTrue($course->isIncompletedBy($employee));

        CourseCompletion::factory([
           'course_id' => $course,
           'employee_id' => $employee
        ])->create();

        $this->assertTrue($course->fresh()->isCompletedBy($employee));
        $this->assertFalse($course->fresh()->isIncompletedBy($employee));
    }

    /** @test */
    public function it_can_be_scoped_to_only_completed_or_incompleted_by_the_specific_employee_courses()
    {
        $courses = Course::factory()->count($count = 3)->create();

        $completion = CourseCompletion::factory([
            'course_id' => $courses->first(),
            'employee_id' => $employee = Employee::factory()->create()
        ])->create();

        $this->assertCount($count, Course::all());
        $this->assertCount($completion->count(), Course::completedBy($employee)->get());
        $this->assertCount($count - $completion->count(), Course::incompletedBy($employee)->get());
    }
}
