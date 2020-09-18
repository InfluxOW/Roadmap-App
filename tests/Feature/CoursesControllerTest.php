<?php

namespace Tests\Feature;

use App\Jobs\ProcessSuggestedCourse;
use App\Models\Course;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CoursesControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;
    public $courses;
    public $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = Employee::factory()->create();
        $this->manager = Manager::factory()->create();
        $this->admin = Admin::factory()->create();

        $this->attributes = Course::factory()->raw();
        $this->courses = Course::factory()->count(3)->create();
    }

    /** @test */
    public function a_guest_cannot_perform_any_actions()
    {
        $this->get(route('courses.index'))
            ->assertRedirect(route('login'));

        $this->get(route('courses.show', $this->courses->first()))
            ->assertRedirect(route('login'));

        $this->post(route('courses.suggest'), ['source' => 'http://test.com'])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_employee_cannot_view_courses()
    {
        $this->actingAs($this->employee, 'sanctum')
            ->get(route('courses.index'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_courses()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->get(route('courses.index'))
            ->assertOk()
            ->assertJsonCount($this->courses->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']
                ]
            ]);
    }

    /** @test */
    public function an_employee_cannot_view_a_specific_course()
    {
        $this->actingAs($this->employee, 'sanctum')
            ->get(route('courses.show', $this->courses->first()))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_a_specific_course()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->manager, 'sanctum')
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']]);
    }

    /** @test */
    public function an_authenticated_user_can_suggest_a_new_course()
    {
        Bus::fake();
        $course = ['source' => 'http://test.com'];

        $this->actingAs($this->employee, 'sanctum')
            ->post(route('courses.suggest'), $course)
            ->assertOk();

        $this->actingAs($this->manager, 'sanctum')
            ->post(route('courses.suggest'), $course)
            ->assertOk();

        $this->actingAs($this->admin, 'sanctum')
            ->post(route('courses.suggest'), $course)
            ->assertOk();
    }

    /** @test */
    public function course_suggestion_dispatches_process_suggested_course_job()
    {
        Bus::fake();
        $course = ['source' => 'http://test.com'];

        $this->actingAs($this->employee, 'sanctum')
            ->post(route('courses.suggest'), $course)
            ->assertOk();

        Bus::assertDispatched(ProcessSuggestedCourse::class);
    }
}
