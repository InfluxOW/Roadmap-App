<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TeamsControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = Manager::factory()->has(Team::factory())->create();
        $this->employee = Employee::factory()->create();
        $this->admin = Admin::factory()->create();
        $this->team = Team::factory()->create();
    }

    /** @test */
    public function an_employee_cannot_perform_any_actions()
    {
        $this->actingAs($this->employee)
            ->post(route('teams.store'))
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->put(route('teams.update', $this->team))
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->delete(route('teams.destroy', $this->team))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_cannot_perform_any_actions()
    {
        $this->actingAs($this->admin)
            ->post(route('teams.store'))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->put(route('teams.update', $this->team))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->delete(route('teams.destroy', $this->team))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_store_a_new_team()
    {
        $attributes = ['name' => 'New Team'];

        $this->actingAs($this->manager)
            ->post(route('teams.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas('teams', $attributes);
    }

    /** @test */
    public function a_manager_can_update_his_team()
    {
        $attributes = ['name' => 'New Team'];

        $this->actingAs($this->manager)
            ->put(route('teams.update', $this->manager->teams->first()), $attributes)
            ->assertOk();

        $this->assertDatabaseHas('teams', $attributes);
    }

    /** @test */
    public function a_manager_cannot_update_not_his_teams()
    {
        $attributes = ['name' => 'New Team'];

        $this->actingAs($this->manager)
            ->put(route('teams.update', $this->team), $attributes)
            ->assertForbidden();

        $this->assertDatabaseMissing('teams', $attributes);
    }

    /** @test */
    public function a_manager_can_delete_his_team()
    {
        $team = $this->manager->teams->first();

        $this->actingAs($this->manager)
            ->delete(route('teams.update', $team))
            ->assertNoContent();

        $this->assertDatabaseMissing('teams', Arr::only($team->toArray(), ['name']));
    }

    /** @test */
    public function a_manager_cannot_delete_not_his_team()
    {
        $this->actingAs($this->manager)
            ->delete(route('teams.update', $this->team))
            ->assertForbidden();

        $this->assertDatabaseHas('teams', Arr::only($this->team->toArray(), ['name']));
    }
}
