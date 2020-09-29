<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Preset;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseAssignmentsControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = Manager::factory()->has(Preset::factory()->hasAttached(Course::factory(), ['assigned_at' => now()]))->create();
        $this->employee = Employee::factory()->create();
        $this->admin = Admin::factory()->create();
    }

    /** @test */
    public function an_employee_cannot_assign_courses_to_the_presets()
    {
        $this->actingAs($this->employee)
            ->post(route('presets.courses.assign', Preset::first()))
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->delete(route('presets.courses.unassign', [Preset::first(), Course::first()]))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_who_owns_a_preset_can_assign_courses_to_it()
    {
        $course = Course::factory()->create();
        $preset = Preset::first();

        $this->actingAs($this->manager)
            ->post(route('presets.courses.assign', $preset), ['course' => $course->slug])
            ->assertOk();

        $this->assertDatabaseHas('preset_courses', ['course_id' => $course->id, 'preset_id' => $preset->id]);
    }

    /** @test */
    public function a_manager_who_owns_a_preset_can_unassign_courses_from_it()
    {
        $preset = Preset::first();
        $course = $preset->courses->first();

        $this->actingAs($this->manager)
            ->delete(route('presets.courses.unassign', [$preset, $course]))
            ->assertOk();

        $this->assertDatabaseMissing('preset_courses', ['course_id' => $course->id, 'preset_id' => $preset->id]);
    }

    /** @test */
    public function a_manager_cannot_assign_courses_to_presets_that_are_not_his()
    {
        $preset = Preset::factory()->create();

        $this->actingAs($this->manager)
            ->post(route('presets.courses.assign', $preset))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_cannot_unassign_courses_from_presets_that_are_not_his()
    {
        $preset = Preset::factory()->hasAttached(Course::factory(), ['assigned_at' => now()])->create();
        $course = $preset->courses->first();

        $this->actingAs($this->manager)
            ->delete(route('presets.courses.unassign', [$preset, $course]))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_assign_courses_to_any_presets()
    {
        $preset = Preset::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('presets.courses.assign', $preset), ['course' => $course->slug])
            ->assertOk();

        $this->assertDatabaseHas('preset_courses', ['course_id' => $course->id, 'preset_id' => $preset->id]);
    }

    /** @test */
    public function an_admin_can_unassign_courses_from_any_presets()
    {
        $preset = Preset::factory()->hasAttached(Course::factory(), ['assigned_at' => now()])->create();
        $course = $preset->courses->first();

        $this->actingAs($this->admin)
            ->delete(route('presets.courses.unassign', [$preset, $course]))
            ->assertOk();
        $this->assertDatabaseMissing('preset_courses', ['course_id' => $course->id, 'preset_id' => $preset->id]);
    }
}
