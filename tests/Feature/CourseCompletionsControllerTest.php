<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseCompletionsControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;
    public $courses;

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
    public function a_guest_cannot_perform_any_actions()
    {
        $this->post(route('course.complete', Course::first()))
            ->assertRedirect(route('login'));

        $this->delete(route('course.incomplete', Course::first()))
            ->assertRedirect(route('login'));

        $this->put(route('completion.update', Course::first()))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_manager_cannot_perform_any_actions()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->post(route('course.complete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->manager, 'sanctum')
            ->delete(route('course.incomplete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->manager, 'sanctum')
            ->put(route('completion.update', Course::first()), ['rating' => 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_cannot_perform_any_actions()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->post(route('course.complete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->admin, 'sanctum')
            ->delete(route('course.incomplete', Course::first()))
            ->assertForbidden();

        $this->actingAs($this->admin, 'sanctum')
            ->put(route('completion.update', Course::first()), ['rating' => 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_can_complete_an_incompleted_course()
    {
        $this->actingAs($this->employee, 'sanctum')
            ->post(route('course.complete', Course::first()))
            ->assertOk();

        $this->assertTrue($this->employee->fresh()->hasCompletedCourse(Course::first()));
        $this->assertDatabaseHas("employee_course_completions", ['employee_id' => $this->employee->id, 'course_id' => Course::first()->id]);
    }

    /** @test */
    public function an_employee_cannot_complete_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh(), 'sanctum')
            ->post(route('course.complete', Course::first()))
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_cannot_complete_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee->fresh(), 'sanctum')
            ->post(route('course.complete', $course))
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_can_incomplete_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh(), 'sanctum')
            ->delete(route('course.incomplete', Course::first()))
            ->assertOk();

        $this->assertTrue($this->employee->fresh()->hasNotCompletedCourse(Course::first()));
        $this->assertDatabaseMissing("employee_course_completions", ['employee_id' => $this->employee->id, 'course_id' => Course::first()->id]);
    }

    /** @test */
    public function an_employee_cannot_incomplete_an_incompleted_course()
    {
        $this->actingAs($this->employee, 'sanctum')
            ->delete(route('course.incomplete', Course::first()))
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_cannot_incomplete_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee, 'sanctum')
            ->delete(route('course.incomplete', $course))
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_can_rate_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh(), 'sanctum')
            ->put(route('completion.update', Course::first()), ['rating' => $rating = 5])
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
        $this->actingAs($this->employee, 'sanctum')
            ->put(route('completion.update', Course::first()), ['rating' => $rating = 5])
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_cannot_rate_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee, 'sanctum')
            ->put(route('completion.update', $course), ['rating' => $rating = 5])
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_can_attach_a_certificate_to_a_completed_course()
    {
        $this->employee->complete(Course::first());

        $this->actingAs($this->employee->fresh(), 'sanctum')
            ->put(route('completion.update', Course::first()), ['certificate' => $certificate = "http://test.com/certificate.jpg"])
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
        $this->actingAs($this->employee, 'sanctum')
            ->put(route('completion.update', Course::first()), ['certificate' => $certificate = "http://test.com/certificate.jpg"])
            ->assertStatus(422);
    }

    /** @test */
    public function an_employee_cannot_attach_a_certificate_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {
        $course = Course::factory()->create();

        $this->actingAs($this->employee, 'sanctum')
            ->put(route('completion.update', $course), ['certificate' => $certificate = "http://test.com/certificate.jpg"])
            ->assertStatus(422);
    }
}
