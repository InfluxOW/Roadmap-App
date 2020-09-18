<?php

namespace Tests\Feature\Admin;

use App\Jobs\ProcessSuggestedCourse;
use App\Models\Course;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CoursesControllerTest extends TestCase
{
    public $admin;
    public $courses;
    public $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->attributes = Course::factory()->raw();
        $this->courses = Course::factory()->count(3)->create();
    }

    /** @test */
    public function an_admin_can_view_courses()
    {
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
    public function an_admin_can_view_a_specific_course()
    {
        $this->actingAs($this->admin, 'sanctum')
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
    public function an_admin_can_update_a_specific_course()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->patch(route('courses.update', $this->courses->first()), $this->attributes)
            ->assertOk();

        $this->assertDatabaseHas('courses', $this->attributes);
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
}
