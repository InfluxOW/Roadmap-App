<?php

namespace Tests\Feature;

use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class ManagersControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    public function an_employee_cannot_perform_any_actions()
    {
        $this->actingAs(Employee::first())
            ->get(route('managers.show', Manager::first()))
            ->assertForbidden();

        $this->actingAs(Employee::first())
            ->get(route('managers.index'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_cannot_view_all_managers_list()
    {
        $this->actingAs(Manager::first())
            ->get(route('managers.index'))
            ->assertRedirect(route('managers.show', Manager::first()));
    }

    /** @test */
    public function a_manager_can_view_his_profile()
    {
        $manager = Manager::first();

        $this->actingAs($manager)
            ->get(route('managers.show', $manager))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams'
                ]
            ])
            ->assertJsonFragment(['name' => $manager->name]);
    }

    /** @test */
    public function a_manager_cannot_view_other_managers_profiles()
    {
        $this->actingAs(Manager::first())
            ->get(route('managers.show', Manager::all()->second()))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_all_managers_list()
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->get(route('managers.index'))
            ->assertOk();

        // TODO: add more assertions
    }

    /** @test */
    public function an_admin_can_view_any_manager_profile()
    {
        $manager = Manager::first();
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->get(route('managers.show', $manager))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams'
                ]
            ])
            ->assertJsonFragment(['name' => $manager->name]);
    }
}
