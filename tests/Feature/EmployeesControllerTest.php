<?php

namespace Tests\Feature;

use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class EmployeesControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    public function a_manager_can_view_his_employee_list()
    {
        $manager = Manager::first();

        $this->actingAs($manager)
            ->get(route('employees.index'))->assertOk()
            ->assertJsonCount($manager->employees->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams', 'technologies', 'development_directions'
                    ]
                ]
            ])
            ->assertJson(['data' => $manager->employees->map->only('name')->toArray()]);
    }

    /** @test */
    public function a_manager_can_view_his_employee_profile()
    {
        $manager = Manager::first();
        $employee = $manager->employees->first();

        $this->actingAs($manager)
            ->get(route('employees.show', $employee))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams', 'technologies', 'development_directions'
                ]
            ])
            ->assertJsonFragment(['name' => $employee->name]);
    }

    /** @test */
    public function a_manager_cannot_view_profiles_of_another_manager_employees()
    {
        $manager = Manager::first();
        $employee = Employee::all()->except($manager->employees->pluck('id')->toArray())->first();

        $this->actingAs($manager)
            ->get(route('employees.show', $employee))
            ->assertForbidden();
    }

    /** @test */
    public function an_employee_cannot_view_all_employees()
    {
        $this->actingAs(Employee::first())
            ->get(route('employees.index'))
            ->assertRedirect(route('employees.show', Employee::first()));
    }

    /** @test */
    public function an_employee_can_view_his_profile()
    {
        $employee = Employee::first();

        $this->actingAs($employee)
            ->get(route('employees.show', $employee))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams', 'technologies', 'development_directions'
                ]
            ])
            ->assertJsonFragment(['name' => $employee->name]);
    }

    /** @test */
    public function an_employee_cannot_view_another_employee_profile()
    {
        $this->actingAs(Employee::first())
            ->get(route('employees.show', Employee::all()->second()))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_all_employees_list()
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->get(route('employees.index'))
            ->assertOk()
            ->assertJsonCount(Employee::count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams', 'technologies', 'development_directions', 'company'
                    ]
                ]
            ]);
    }

    /** @test */
    public function an_admin_can_view_any_employee_profile()
    {
        $admin = Admin::factory()->create();
        $employee = Employee::first();

        $this->actingAs($admin)
            ->get(route('employees.show', $employee))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'name', 'username', 'email', 'role', 'sex', 'birthday', 'position', 'teams', 'technologies', 'development_directions', 'company'
                ]
            ])
            ->assertJsonFragment(['name' => $employee->name]);
    }
}
