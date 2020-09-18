<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Preset;
use App\Models\Team;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RoadmapsControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;
    public $preset;
    public $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $company = Company::factory()->create();
        $this->employee = Employee::factory(['company_id' => $company])->create();
        $this->manager = Manager::factory(['company_id' => $company])->create();
        $this->admin = Admin::factory()->create();
        $this->preset = Preset::factory()->create();

        $team = Team::factory(['owner_id' => $this->manager, 'company_id' => $company])->create();
        $team->employees()->attach($this->employee, ['assigned_at' => now()]);

        $this->attributes = ['employee' => $this->employee->username, 'preset' => $this->preset->slug];
    }

    /** @test */
    public function an_employee_cannot_perform_any_actions()
    {
        $this->actingAs($this->employee)
            ->post(route('roadmaps.store'), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->delete(route('roadmaps.destroy', $this->attributes))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_cannot_perform_any_actions()
    {
        $this->actingAs($this->admin)
            ->post(route('roadmaps.store'), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->delete(route('roadmaps.destroy', $this->attributes))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_create_a_roadmap_for_the_employee()
    {
        $this->actingAs($this->manager)
            ->post(route('roadmaps.store'), $this->attributes)
            ->assertCreated();

        $this->assertDatabaseHas(
            'employee_roadmaps',
            [
                'preset_id' => $this->preset->id,
                'employee_id' => $this->employee->id,
                'manager_id' => $this->manager->id,
            ]
        );
    }

    /** @test */
    public function a_manager_cannot_create_a_roadmap_for_the_employee_which_doesnt_belong_to_any_of_his_teams()
    {
        $manager = Manager::factory()->create();

        $this->actingAs($manager)
            ->post(route('roadmaps.store'), $this->attributes)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function a_manager_can_delete_roadmap_of_the_employee()
    {
        $this->manager->createRoadmap($this->preset, $this->employee);

        $this->actingAs($this->manager)
            ->delete(route('roadmaps.destroy', $this->attributes))
            ->assertNoContent();

        $this->assertDatabaseMissing(
            'employee_roadmaps',
            [
                'preset_id' => $this->preset->id,
                'employee_id' => $this->employee->id,
                'manager_id' => $this->manager->id,
            ]
        );
    }

    /** @test */
    public function a_manager_cannot_delete_a_roadmap_of_the_employee_which_doesnt_belong_to_any_of_his_teams()
    {
        $this->manager->createRoadmap($this->preset, $this->employee);
        $manager = Manager::factory()->create();

        $this->actingAs($manager)
            ->delete(route('roadmaps.destroy', $this->attributes))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function a_manager_cannot_delete_nonexistent_roadmap()
    {
        $this->assertDatabaseMissing(
            'employee_roadmaps',
            [
                'preset_id' => $this->preset->id,
                'employee_id' => $this->employee->id,
                'manager_id' => $this->manager->id,
            ]
        );

        $this->actingAs($this->manager)
            ->delete(route('roadmaps.destroy', $this->attributes))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
