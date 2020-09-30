<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TeamMembersControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = Manager::factory()->has(
            Team::factory()->hasAttached(Employee::factory(), ['assigned_at' => now()], 'employees')
        )->create();
        $this->employee = Employee::factory()->create();
        $this->admin = Admin::factory()->create();
        $this->team = Team::factory()->create();
    }

    /** @test */
    public function an_employee_cannot_perform_any_actions()
    {
        $this->actingAs($this->employee)
            ->post(route('team.employees.store', $this->team))
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->delete(route('team.employees.destroy', [$this->manager->teams->first(), $this->manager->teams->first()->employees->first()]))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_cannot_perform_any_actions()
    {
        $this->actingAs($this->admin)
            ->post(route('team.employees.store', $this->team))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->delete(route('team.employees.destroy', [$this->manager->teams->first(), $this->manager->teams->first()->employees->first()]))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_assign_new_employees_to_his_teams()
    {
        $this->actingAs($this->manager)
            ->post(route('team.employees.store', $this->manager->teams->first()), ['employee' => $this->employee->username])
            ->assertOk();

        $this->assertTrue($this->manager->teams->first()->employees->contains($this->employee));
    }

    /** @test */
    public function a_manager_cannot_assign_an_employee_twice()
    {
        $team = $this->manager->teams->first();

        $this->actingAs($this->manager)
            ->post(route('team.employees.store', $team), ['employee' => $team->employees->first()->username])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function a_manager_cannot_assign_new_employees_to_not_his_teams()
    {
        $this->actingAs($this->manager)
            ->post(route('team.employees.store', $this->team))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_unassign_employees_from_his_teams()
    {
        $team = $this->manager->teams->first();

        $this->actingAs($this->manager)
            ->delete(route('team.employees.destroy', [$team, $team->employees->first()]))
            ->assertOk();

        $this->assertFalse($team->fresh()->employees->contains($team->employees->first()));
    }

    /** @test */
    public function a_manager_cannot_unassign_employees_from_not_his_teams()
    {
        $team = $this->manager->teams->first();

        $this->actingAs(Manager::factory()->create())
            ->delete(route('team.employees.destroy', [$team, $team->employees->first()]))
            ->assertForbidden();
    }
}
