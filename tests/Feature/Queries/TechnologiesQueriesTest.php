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

    /** @test */
    public function technologies_can_be_sorted_by_name()
    {
        $technologies = Technology::take(20)->get()->sortBy('name')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['sort' => 'name']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $technologies,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function technologies_can_be_sorted_by_courses_count()
    {
        $technologies = Technology::withCount('courses')->take(20)->groupBy('courses_count')->groupBy('name')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['sort' => 'courses_count,name']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $technologies,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function a_user_can_specify_what_attributes_should_be_returned()
    {
        $attributes = "name";

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['show[technology]' => $attributes]
                )
            )
            ->assertOk();

        $this->assertEquals(
            json_decode($response->content(), true)['data'],
            Technology::take(20)->get()->map->only(explode(',', $attributes))->toArray()
        );
    }

    /** @test */
    public function a_user_can_specify_how_many_technology_courses_should_be_returned()
    {
        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['take[courses]' => $take = 1]
                )
            )
            ->assertOk();

        $this->assertEquals(
            count(json_decode($response->content(), true)['data'][0]['courses']),
            $take
        );
    }

    /** @test */
    public function a_user_can_specify_how_many_technology_directions_should_be_returned()
    {
        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['take[directions]' => $take = 1]
                )
            )
            ->assertOk();

        $this->assertEquals(
            count(json_decode($response->content(), true)['data'][0]['directions']),
            $take
        );
    }

    /** @test */
    public function a_user_can_specify_how_many_technology_possessed_by_should_be_returned()
    {
        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'technologies.index',
                    ['take[possessed_by]' => $take = 0]
                )
            )
            ->assertOk();

        $this->assertEquals(
            count(json_decode($response->content(), true)['data'][0]['possessed_by']),
            $take
        );
    }
}
