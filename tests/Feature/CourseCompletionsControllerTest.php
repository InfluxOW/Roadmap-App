<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CourseCompletionsControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = Employee::factory()->has(
            Roadmap::factory()->for(
                Preset::factory()->hasAttached(
                    Course::factory()->count(3),
                    ['assigned_at' => now()]
                )
            )
        )->create();
        $this->manager = Manager::factory()->create();
        $this->admin = Admin::factory()->create();
    }

    /** @test */
    public function a_manager_cannot_perform_any_actions()
    {
        $this->actingAs($this->manager)
            ->post(route('courses.complete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->manager)
            ->delete(route('courses.incomplete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->manager)
            ->put(route('completions.update', Course::first()), ['rating' => 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_cannot_perform_any_actions()
    {
        $this->actingAs($this->admin)
            ->post(route('courses.complete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->delete(route('courses.incomplete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->put(route('completions.update', Course::first()), ['rating' => 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_can_complete_an_incompleted_course()
    {
        $this->actingAs($this->employee)
            ->post(route('courses.complete', Course::first()))
            ->assertOk();

        $this->assertTrue($this->employee->fresh()->hasCompletedCourse(Course::first()));
        $this->assertDatabaseHas("employee_course_completions", ['employee_id' => $this->employee->id, 'course_id' => Course::first()->id]);
    }

    /** @test */
    public function an_employee_cannot_complete_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh())
            ->post(route('courses.complete', Course::first()))
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_cannot_complete_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee->fresh())
            ->post(route('courses.complete', $course))
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_can_incomplete_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh())
            ->delete(route('courses.incomplete', Course::first()))
            ->assertOk();

        $this->assertTrue($this->employee->fresh()->hasNotCompletedCourse(Course::first()));
        $this->assertDatabaseMissing("employee_course_completions", ['employee_id' => $this->employee->id, 'course_id' => Course::first()->id]);
    }

    /** @test */
    public function an_employee_cannot_incomplete_an_incompleted_course()
    {
        $this->actingAs($this->employee)
            ->delete(route('courses.incomplete', Course::first()))
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_cannot_incomplete_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee)
            ->delete(route('courses.incomplete', $course))
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_can_rate_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh())
            ->put(route('completions.update', Course::first()), ['rating' => $rating = 5])
            ->assertOk();

        $this->assertEquals(Course::first()->average_rating, $rating);
        $this->assertDatabaseHas('employee_course_completions', [
            'employee_id' => $this->employee->id,
            'course_id' => Course::first()->id,
            'rating' => $rating
        ]);
    }

    /** @test */
    public function an_employee_cannot_rate_an_incompleted_course()
    {
        $this->actingAs($this->employee)
            ->put(route('completions.update', Course::first()), ['rating' => $rating = 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_cannot_rate_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee)
            ->put(route('completions.update', $course), ['rating' => $rating = 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_can_attach_a_certificate_to_a_completed_course()
    {
        $this->withoutExceptionHandling();
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh())
            ->put(route('completions.update', Course::first()), ['certificate' => $certificate = "http://test.com/certificate.jpg"])
            ->assertOk();

        $this->assertDatabaseHas('employee_course_completions', [
            'employee_id' => $this->employee->id,
            'course_id' => Course::first()->id,
            'certificate' => $certificate
        ]);
    }

    /** @test */
    public function an_employee_cannot_attach_a_certificate_to_an_incompleted_course()
    {
        $this->actingAs($this->employee)
            ->put(route('completions.update', Course::first()), ['certificate' => $certificate = "http://test.com/certificate.jpg"])
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_cannot_attach_a_certificate_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee)
            ->put(route('completions.update', $course), ['certificate' => $certificate = "http://test.com/certificate.jpg"])
            ->assertForbidden();
    }
}
