<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
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

        $this->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertRedirect(route('login'));

        $this->delete(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_employee_cannot_perform_any_actions()
    {
        $this->actingAs($this->employee, 'api')
            ->get(route('courses.index'))
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->get(route('courses.show', $this->courses->first()))
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->post(route('courses.store'), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->delete(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_courses()
    {
        $this->actingAs($this->admin, 'api')
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
        $this->actingAs($this->manager, 'api')
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
    public function an_admin_can_view_a_specific_course()
    {
        $this->actingAs($this->admin, 'api')
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating']]);
    }

    /** @test */
    public function a_manager_can_view_a_specific_course()
    {
        $this->actingAs($this->manager, 'api')
            ->get(route('courses.show', $this->courses->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'source', 'level', 'completed_by', 'average_rating']]);
    }

    /** @test */
    public function an_admin_can_create_a_new_course()
    {
        $this->actingAs($this->admin, 'api')
            ->post(route('courses.store'), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseCount('courses', $this->courses->count() + 1);
        $this->assertDatabaseHas('courses', $this->attributes);
    }

    /** @test */
    public function a_manager_can_create_a_new_course()
    {
        $this->actingAs($this->manager, 'api')
            ->post(route('courses.store'), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseCount('courses', $this->courses->count() + 1);
        $this->assertDatabaseHas('courses', $this->attributes);
    }

    /** @test */
    public function an_admin_can_update_a_specific_course()
    {
        $this->actingAs($this->admin, 'api')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseHas('courses', $this->attributes);
    }

    /** @test */
    public function a_course_owner_can_update_it()
    {
        $course = Course::factory()->create(['manager_id' => $this->manager]);

        $this->actingAs($this->manager, 'api')
            ->patch(route('courses.update', $course), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseHas('courses', $this->attributes);
    }

    /** @test */
    public function a_manager_cannot_update_a_course_that_doesnt_belongs_to_him()
    {
        $this->actingAs($this->manager, 'api')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertForbidden();

        $this->assertDatabaseMissing('courses', $this->attributes);
    }


    /** @test */
    public function an_admin_can_delete_a_specific_course()
    {
        $course = $this->courses->first();
        $this->actingAs($this->admin, 'api')
            ->delete(route('courses.destroy', $course))
            ->assertRedirect();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /** @test */
    public function a_course_owner_can_delete_it()
    {
        $course = Course::factory()->create(['manager_id' => $this->manager]);

        $this->actingAs($this->manager, 'api')
            ->delete(route('courses.destroy', $course))
            ->assertRedirect();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /** @test */
    public function a_manager_cannot_delete_a_course_that_doesnt_belongs_to_him()
    {
        $course = $this->courses->first();
        $this->actingAs($this->manager, 'api')
            ->delete(route('courses.update', $course))
            ->assertForbidden();

        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }
}
