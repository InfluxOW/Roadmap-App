<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\DevelopmentDirection;
use App\Models\Team;
use App\Models\Technology;
use App\Models\UserTypes\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company()
    {
        $employee = Employee::factory()->for(Company::factory())->create();

        $this->assertEquals(Company::first(), $employee->company);
        $this->assertTrue($employee->company->is(Company::first()));
    }

    /** @test */
    public function it_may_have_many_course_completions()
    {
        $employee = Employee::factory()->has(
            CourseCompletion::factory()->count($count = 3),
            'completions'
        )->create();

        $this->assertTrue($employee->completions->contains(CourseCompletion::first()));
        $this->assertInstanceOf(CourseCompletion::class, $employee->completions->first());
        $this->assertCount($count, $employee->completions);
    }

    /** @test */
    public function it_may_have_many_roadmaps()
    {
        $employee = Employee::factory()->has(
            CourseCompletion::factory()->count($count = 3),
            'completions'
        )->create();

        $this->assertTrue($employee->completions->contains(CourseCompletion::first()));
        $this->assertInstanceOf(CourseCompletion::class, $employee->completions->first());
        $this->assertCount($count, $employee->completions);
    }

    /** @test */
    public function it_may_belong_to_many_development_directions()
    {
        $employee = Employee::factory()->hasAttached(
            DevelopmentDirection::factory()->count($count = 3),
            [],
            'directions'
        )->create();

        $this->assertTrue($employee->directions->contains(DevelopmentDirection::first()));
        $this->assertInstanceOf(DevelopmentDirection::class, $employee->directions->first());
        $this->assertCount($count, $employee->directions);
    }

    /** @test */
    public function it_may_belong_to_many_development_technologies()
    {
        $employee = Employee::factory()->hasAttached(
            Technology::factory()->count($count = 3)
        )->create();

        $this->assertTrue($employee->technologies->contains(Technology::first()));
        $this->assertInstanceOf(Technology::class, $employee->technologies->first());
        $this->assertCount($count, $employee->technologies);
    }

    /** @test */
    public function it_may_belong_to_many_teams()
    {
        $employee = Employee::factory()->hasAttached(
            Team::factory()->count($count = 3),
            ['assigned_at' => now()]
        )->create();

        $this->assertTrue($employee->teams->contains(Team::first()));
        $this->assertInstanceOf(Team::class, $employee->teams->first());
        $this->assertCount($count, $employee->teams);
    }

    /** @test */
    public function it_knows_if_it_has_completed_or_incompleted_specific_course()
    {
        $employee = Employee::factory()->create();
        $course = Course::factory()->create();

        $this->assertFalse($employee->hasCompletedCourse($course));
        $this->assertTrue($employee->hasNotCompletedCourse($course));

        CourseCompletion::factory([
            'course_id' => $course,
            'employee_id' => $employee
        ])->create();

        $this->assertTrue($employee->fresh()->hasCompletedCourse($course));
        $this->assertFalse($employee->fresh()->hasNotCompletedCourse($course));
    }

    /** @test */
    public function it_can_be_scoped_to_only_completed_a_specific_course_employees()
    {
        $employees = Employee::factory()->count($count = 3)->create();
        $course = Course::factory()->create();
        $employees->first()->complete($course);

        $this->assertCount($count, Employee::all());
        $this->assertCount(1, Employee::withCompletedCourse($course)->get());
    }

    /** @test */
    public function it_can_complete_an_incompleted_course()
    {
        $employee = Employee::factory()->create();
        $course = Course::factory()->create();

        $this->assertFalse($employee->hasCompletedCourse($course));

        $employee->complete($course);

        $this->assertTrue($employee->fresh()->hasCompletedCourse($course));
    }

    /** @test */
    public function it_can_incomplete_a_completed_course()
    {
        $employee = Employee::factory()->create();
        $course = Course::factory()->create();
        $employee->complete($course);

        $this->assertTrue($employee->fresh()->hasCompletedCourse($course));

        $employee->fresh()->incomplete($course);

        $this->assertFalse($employee->fresh()->hasCompletedCourse($course));
    }

    /** @test */
    public function it_can_rate_a_completed_course()
    {
        $employee = Employee::factory()->create();
        $course = Course::factory()->create();
        $employee->complete($course);

        $this->assertEquals(0, $course->average_rating);

        $employee->fresh()->rate($course, $rating = 10);

        $this->assertEquals($rating, $course->fresh()->average_rating);
    }
}
