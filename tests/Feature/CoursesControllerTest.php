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
    public function an_employee_cannot_view_courses()
    {
        $this->actingAs($this->employee)
            ->get(route('courses.index'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_courses()
    {
        $this->actingAs($this->manager)
            ->get(route('courses.index'))
            ->assertOk()
            ->assertJsonCount($this->courses->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'slug', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']
                ]
            ]);
    }

    /** @test */
    public function an_admin_can_view_courses()
    {
        $this->actingAs($this->admin)
            ->get(route('courses.index'))
            ->assertOk()
            ->assertJsonCount($this->courses->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'slug', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']
                ]
            ]);
    }

    /** @test */
    public function an_employee_cannot_view_a_specific_course()
    {
        $this->actingAs($this->employee)
            ->get(route('courses.show', $this->courses->first()))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_a_specific_course()
    {
        $this->actingAs($this->manager)
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'slug', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']]);
    }

    /** @test */
    public function an_admin_can_view_a_specific_course()
    {
        $this->actingAs($this->admin)
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'slug', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']]);
    }

    /** @test */
    public function an_authenticated_user_can_suggest_a_new_course()
    {
        Bus::fake();
        $course = ['source' => 'http://test.com'];

        $this->actingAs($this->employee)
            ->post(route('courses.suggest'), $course)
            ->assertOk();

        $this->actingAs($this->manager)
            ->post(route('courses.suggest'), $course)
            ->assertOk();

        $this->actingAs($this->admin)
            ->post(route('courses.suggest'), $course)
            ->assertOk();
    }

    /** @test */
    public function course_suggestion_dispatches_process_suggested_course_job()
    {
        Bus::fake();
        $course = ['source' => 'http://test.com'];

        $this->actingAs($this->employee)
            ->post(route('courses.suggest'), $course)
            ->assertOk();

        Bus::assertDispatched(ProcessSuggestedCourse::class);
    }
}
