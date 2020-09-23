<?php

namespace Tests\Feature\Queries;

use App\Models\Course;
use App\Models\DevelopmentDirection;
use App\Models\Technology;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TechnologiesQueriesTest extends TestCase
{
    public $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->manager = Manager::first();
    }

    /** @test */
    public function technologies_can_be_filtered_by_name()
    {
        $name = Technology::first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[name]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function technologies_can_be_filtered_by_directions_name()
    {
        $name = DevelopmentDirection::first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[directions]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(DevelopmentDirection::first()->technologies->count(), 'data');
    }

    /** @test */
    public function technologies_can_be_filtered_by_directions_slug()
    {
        $slug = DevelopmentDirection::first()->slug;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[directions]' => $slug]
                )
            )
            ->assertOk()
            ->assertJsonCount(DevelopmentDirection::first()->technologies->count(), 'data');
    }

    /** @test */
    public function technologies_can_be_filtered_by_courses_name()
    {
        $name = Course::first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[courses]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::first()->technologies->count(), 'data');
    }

    /** @test */
    public function technologies_can_be_filtered_by_courses_slug()
    {
        $slug = Course::first()->slug;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[courses]' => $slug]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::first()->technologies->count(), 'data');
    }

    /** @test */
    public function technologies_can_be_filtered_by_employees_name()
    {
        $employee = $this->manager->employees->first();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[employees]' => $employee->name]
                )
            )
            ->assertOk()
            ->assertJsonCount($employee->technologies->count(), 'data');
    }

    /** @test */
    public function technologies_can_be_filtered_by_employees_username()
    {
        $employee = $this->manager->employees->first();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[employees]' => $employee->username]
                )
            )
            ->assertOk()
            ->assertJsonCount($employee->technologies->count(), 'data');
    }

    /** @test */
    public function technologies_can_be_filtered_by_employees_email()
    {
        $employee = $this->manager->employees->first();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[employees]' => $employee->email]
                )
            )
            ->assertOk()
            ->assertJsonCount($employee->technologies->count(), 'data');
    }

    /** @test */
    public function a_manager_cannot_filter_technologies_by_not_his_employees()
    {
        $name = Employee::all()->except($this->manager->employees->pluck('id')->toArray())->first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['filter[employees]' => $name]
                )
            )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
