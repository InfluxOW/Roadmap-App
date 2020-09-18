<?php

namespace Tests\Feature;

use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class EmployeeDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    public function an_employee_can_view_his_dashboard()
    {
        $employee = Employee::first();

        $this->actingAs($employee, 'sanctum')
            ->get(route('dashboard.employee', $employee))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['name', 'username', 'email', 'role', 'company', 'sex', 'birthday', 'position'],
                    'teams' => ['*' => []],
                    'roadmaps' => [
                        '*' => [
                            'preset' => [
                                'name',
                                'description',
                                'link',
                                'courses' => [
                                    '*' => [
                                        '*' => [
                                            '*' => [
                                                'name',
                                                'description',
                                                'source',
                                                'level',
                                                'link',
                                                'average_rating'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'assigned_at'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function an_employee_cannot_view_anyone_else_dashboard()
    {
        $this->actingAs(Employee::first(), 'sanctum')
            ->get(route('dashboard.employee', Employee::all()->second()))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_his_subordinates_dashboard()
    {
        $manager = Manager::first();
        $employee = $manager->employees->first();

        $this->assertTrue($manager->hasEmployee($employee));

        $this->actingAs($employee, 'sanctum')
            ->get(route('dashboard.employee', $employee))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['name', 'username', 'email', 'role', 'company', 'sex', 'birthday', 'position'],
                    'teams' => ['*' => []],
                    'roadmaps' => [
                        '*' => [
                            'preset' => [
                                'name',
                                'description',
                                'link',
                                'courses' => [
                                    '*' => [
                                        '*' => [
                                            '*' => [
                                                'name',
                                                'description',
                                                'source',
                                                'level',
                                                'link',
                                                'average_rating'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'assigned_at'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_manager_cannot_view_other_managers_subordinates_dashboard()
    {
        $manager = Manager::all()->third();
        $employee = Employee::first();

        $this->assertFalse($manager->hasEmployee($employee));

        $this->actingAs($manager, 'sanctum')
            ->get(route('dashboard.employee', $employee))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_every_employee_dashboard()
    {
        $admin = Admin::factory()->create();
        $employee = Employee::first();

        $this->actingAs($admin, 'sanctum')
            ->get(route('dashboard.employee', $employee))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['name', 'username', 'email', 'role', 'company', 'sex', 'birthday', 'position'],
                    'teams' => ['*' => []],
                    'roadmaps' => [
                        '*' => [
                            'preset' => [
                                'name',
                                'description',
                                'link',
                                'courses' => [
                                    '*' => [
                                        '*' => [
                                            '*' => [
                                                'name',
                                                'description',
                                                'source',
                                                'level',
                                                'link',
                                                'average_rating'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'assigned_at'
                        ]
                    ]
                ]
            ]);
    }
}
