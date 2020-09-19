<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Preset;
use App\Models\Roadmap;
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

        $this->seed();
        $this->manager = Manager::first();
        $this->employee = $this->manager->employees->first();
        $this->admin = Admin::factory()->create();
        $this->preset = Preset::all()->except($this->employee->presets->pluck('id')->toArray())->first();

        $this->attributes = ['employee' => $this->employee->username, 'preset' => $this->preset->slug];
    }

    /** @test */
    public function an_employee_cannot_manage_roadmaps()
    {
        $this->actingAs($this->employee)
            ->post(route('roadmaps.store'), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->delete(route('roadmaps.destroy', $this->attributes))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_cannot_manage_roadmaps()
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
        Roadmap::factory()->create([
            'employee_id' => $this->employee->id,
            'preset_id' => $this->preset->id,
            'manager_id' => $this->manager->id,
        ]);

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
        Roadmap::factory()->create([
            'employee_id' => $this->employee->id,
            'preset_id' => $this->preset->id,
            'manager_id' => $this->manager->id,
        ]);

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

    /** @test */
    public function an_employee_cannot_view_all_roadmaps()
    {
        $this->actingAs($this->employee)
            ->get(route('roadmaps.index'))
            ->assertRedirect(route('roadmaps.show', $this->employee));
    }

    /** @test */
    public function an_employee_can_view_his_roadmaps()
    {
        $this->actingAs($this->employee)
            ->get(route('roadmaps.show', $this->employee))
            ->assertOk()
            ->assertJsonCount($this->employee->roadmaps->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'preset',
                        'assigned_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function an_employee_cannot_view_other_employees_roadmaps()
    {
        $this->actingAs($this->employee)
            ->get(route('roadmaps.show', Employee::factory()->create()))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_his_employees_roadmaps()
    {
        $this->actingAs($this->manager)
            ->get(route('roadmaps.index'))
            ->assertOk()
            ->assertJsonCount($this->manager->roadmaps->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'preset',
                        'assigned_at',
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_manager_can_view_specific_employee_roadmaps()
    {
        $this->actingAs($this->manager)
            ->get(route('roadmaps.show', $this->employee))
            ->assertOk()
            ->assertJsonCount($this->employee->roadmaps->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'preset',
                        'assigned_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_manager_cannot_view_roadmaps_of_employees_which_doesnt_belong_to_any_of_his_teams()
    {
        $this->actingAs($this->manager)
            ->get(route('roadmaps.show', Employee::factory()->create()))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_all_roadmaps()
    {
        Roadmap::factory()->count(2)->create();

        $this->actingAs($this->admin)
            ->get(route('roadmaps.index'))
            ->assertOk()
            ->assertJsonCount(Roadmap::count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'preset',
                        'assigned_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function an_admin_can_view_specific_employee_roadmaps()
    {
        $this->actingAs($this->admin)
            ->get(route('roadmaps.show', $this->employee))
            ->assertOk()
            ->assertJsonCount($this->employee->roadmaps->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'preset',
                        'assigned_at'
                    ]
                ]
            ]);
    }
}
