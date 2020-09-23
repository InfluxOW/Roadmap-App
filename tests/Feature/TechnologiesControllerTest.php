<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DevelopmentDirection;
use App\Models\Technology;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechnologiesControllerTest extends TestCase
{
    protected $technologies;
    protected $employee;
    protected $manager;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->technologies = Technology::factory()->count(3)
            ->has(Course::factory()->count(3))
            ->has(DevelopmentDirection::factory()->count(2), 'directions')
            ->has(Employee::factory()->count(3))
            ->create();
        $this->employee = Employee::first();
        $this->manager = Manager::factory()->create();
        $this->admin = Admin::factory()->create();
    }

    /** @test */
    public function an_employee_cannot_view_technologies()
    {
        $this->actingAs($this->employee)
            ->get(route('technologies.index'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_view_technologies()
    {
        $this->actingAs($this->manager)
            ->get(route('technologies.index'))
            ->assertOk()
            ->assertJsonCount($this->technologies->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'slug', 'description', 'courses', 'directions', 'possessed_by']
                ]
            ]);
    }

    /** @test */
    public function an_admin_can_view_technologies()
    {
        $this->actingAs($this->admin)
            ->get(route('technologies.index'))
            ->assertOk()
            ->assertJsonCount($this->technologies->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'slug', 'description', 'courses', 'directions', 'possessed_by']
                ]
            ]);
    }
}
