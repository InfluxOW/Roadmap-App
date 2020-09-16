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

        $this->post(route('courses.store'), $this->attributes)
            ->assertRedirect(route('login'));

        $this->post(route('courses.suggest'), ['source' => 'http://test.com'])
            ->assertRedirect(route('login'));

        $this->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertRedirect(route('login'));

        $this->delete(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_employee_cannot_perform_any_course_crud_actions()
    {
        $this->actingAs($this->employee, 'sanctum')
            ->get(route('courses.index'))
            ->assertForbidden();

        $this->actingAs($this->employee, 'sanctum')
            ->get(route('courses.show', $this->courses->first()))
            ->assertForbidden();

        $this->actingAs($this->employee, 'sanctum')
            ->post(route('courses.store'), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee, 'sanctum')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee, 'sanctum')
            ->delete(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_courses()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->admin, 'sanctum')
            ->get(route('courses.index'))
            ->assertOk()
            ->assertJsonCount($this->courses->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating']
                ]
            ]);
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
    public function an_admin_can_view_a_specific_course()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']]);
    }

    /** @test */
    public function a_manager_can_view_a_specific_course()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating', 'technologies']]);
    }

    /** @test */
    public function an_admin_can_create_a_new_course()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->post(route('courses.store'), $this->attributes)
            ->assertCreated();

        $this->assertDatabaseCount('courses', $this->courses->count() + 1);
        $this->assertDatabaseHas('courses', $this->attributes);
    }

    /** @test */
    public function a_manager_cannot_create_a_new_course()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->post(route('courses.store'), $this->attributes)
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_update_a_specific_course()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertOk();

        $this->assertDatabaseHas('courses', $this->attributes);
    }

    /** @test */
    public function a_manager_cannot_update_a_course()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertForbidden();
    }


    /** @test */
    public function an_admin_can_delete_a_specific_course()
    {
        $course = $this->courses->first();
        $this->actingAs($this->admin, 'sanctum')
            ->delete(route('courses.destroy', $course))
            ->assertNoContent();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /** @test */
    public function a_manager_cannot_delete_a_course()
    {
        $this->actingAs($this->manager, 'sanctum')
            ->delete(route('courses.update', $this->courses->first()))
            ->assertForbidden();
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
