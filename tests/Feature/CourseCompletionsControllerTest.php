<?php

namespace Tests\Feature;

use App\Models\Course;
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

        $this->employee = Employee::factory()->create();
        $this->manager = Manager::factory()->create();
        $this->admin = Admin::factory()->create();
        $this->courses = Course::factory()->count(3)->create();
    }

    /** @test */
    public function a_guest_cannot_perform_any_actions()
    {
        $this->post(route('course.complete', $this->courses->first()))
            ->assertRedirect(route('login'));

        $this->delete(route('course.incomplete', $this->courses->first()))
            ->assertRedirect(route('login'));

        $this->put(route('completion.update', $this->courses->first()))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_manager_cannot_perform_any_actions()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->post(route('course.complete', $this->courses->first()))
            ->assertForbidden();

        $this->actingAs($this->manager, 'sanctum')
            ->delete(route('course.incomplete', $this->courses->first()))
            ->assertForbidden();

        $this->actingAs($this->manager, 'sanctum')
            ->put(route('completion.update', $this->courses->first()), ['rating' => 5])
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_can_complete_an_incompleted_course()
    {

    }

    /** @test */
    public function an_employee_cannot_complete_a_completed_course()
    {

    }

    /** @test */
    public function an_employee_cannot_complete_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {

    }

    /** @test */
    public function an_employee_can_incomplete_a_completed_course()
    {

    }

    /** @test */
    public function an_employee_cannot_incomplete_an_incompleted_course()
    {

    }

    /** @test */
    public function an_employee_cannot_incomplete_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {

    }

    /** @test */
    public function an_employee_can_rate_a_completed_course()
    {

    }

    /** @test */
    public function an_employee_cannot_rate_an_incompleted_course()
    {

    }

    /** @test */
    public function an_employee_cannot_rate_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {

    }

    /** @test */
    public function an_employee_can_attach_a_certificate_to_a_completed_course()
    {

    }

    /** @test */
    public function an_employee_cannot_attach_a_certificate_to_an_incompleted_course()
    {

    }

    /** @test */
    public function an_employee_cannot_attach_a_certificate_a_course_that_doesnt_belongs_to_any_of_his_roadmaps()
    {

    }

    /*
     * Check admin privileges
     * */
}
