<?php

namespace Tests\Unit\Middlewares;

use App\Models\Course;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;
use Tests\TestCase;

class RouteWithEmployeeMiddlewareCanOnlyBeReachedByEmployeesTest extends TestCase
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
        $response = $this->createRequestWithEmployeeMiddleware();

        $this->assertEquals($response->status(), 403);
    }

    /** @test */
    public function an_admin_cannot_perform_any_actions()
    {
        $this->actingAs($this->admin);
        $response = $this->createRequestWithEmployeeMiddleware();

        $this->assertEquals($response->status(), 403);
    }

    /** @test */
    public function a_manager_cannot_perform_any_actions()
    {
        $this->actingAs($this->manager);
        $response = $this->createRequestWithEmployeeMiddleware();

        $this->assertEquals($response->status(), 403);
    }

    /** @test */
    public function an_employee_can_perform_any_actions()
    {
        $this->actingAs($this->employee);
        $response = $this->createRequestWithEmployeeMiddleware();

        $this->assertEquals($response->status(), 200);
    }

    protected function createRequestWithEmployeeMiddleware()
    {
        $request = Request::create('admin', 'GET');
        $middleware = new \App\Http\Middleware\Employee();

        return $middleware->handle($request, function () {
            return response([], 200);
        });
    }
}
