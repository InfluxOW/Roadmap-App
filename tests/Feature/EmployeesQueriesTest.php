<?php

namespace Tests\Feature;

use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Tests\TestCase;

class EmployeesQueriesTest extends TestCase
{
    public $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->manager = Manager::first();
    }

    /** @test */
    public function employees_can_be_filtered_by_name()
    {
        $name = $this->manager->employees->first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[name]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function employees_can_be_filtered_by_username()
    {
        $username = $this->manager->employees->first()->username;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[username]' => $username]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['username' => $username]);
    }

    /** @test */
    public function employees_can_be_filtered_by_email()
    {
        $email = $this->manager->employees->first()->email;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[email]' => $email]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['email' => $email]);
    }

    /** @test */
    public function employees_can_be_filtered_by_sex()
    {
        $sex = $this->manager->employees->first()->sex;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[sex]' => $sex]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->employees->where('sex', $sex)->count(), 'data')
            ->assertJsonFragment(['sex' => $sex]);
    }

    /** @test */
    public function employees_can_be_filtered_by_position()
    {
        $position = $this->manager->employees->first()->position;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[position]' => $position]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->employees->where('position', $position)->count(), 'data')
            ->assertJsonFragment(['position' => $position]);
    }

    /** @test */
    public function employees_can_be_filtered_by_used_technologies()
    {
        $technology = $this->manager->employees->first()->technologies->first()->name;
        $employees = $this->manager->employees()->whereHas('technologies', function (Builder $query) use ($technology) {
            return $query->whereName($technology);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[technologies]' => $technology]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_development_directions()
    {
        $direction = $this->manager->employees->first()->directions->first()->name;
        $employees = $this->manager->employees()->whereHas('directions', function (Builder $query) use ($direction) {
            return $query->whereName($direction);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[directions]' => $direction]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_teams()
    {
        $team = $this->manager->employees->first()->teams->first()->name;
        $employees = $this->manager->employees()->whereHas('teams', function (Builder $query) use ($team) {
            return $query->whereName($team);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[teams]' => $team]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_assigned_presets()
    {
        $preset = $this->manager->employees->first()->presets->first()->name;
        $employees = $this->manager->employees()->whereHas('presets', function (Builder $query) use ($preset) {
            return $query->whereName($preset);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[presets]' => $preset]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_assigned_courses()
    {
        $this->withoutExceptionHandling();
        $course = $this->manager->employees->first()->courses->first()->name;
        $employees = $this->manager->employees()->whereHas('courses', function (Builder $query) use ($course) {
            return $query->where('courses.name', $course);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[courses]' => $course]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_completed_courses()
    {
        $course = $this->manager->employees->first()->completions->first()->course->name;
        $employees = $this->manager->employees()->whereHas('completions', function (Builder $query) use ($course) {
            return $query->whereHas('course', function (Builder $query) use ($course) {
                return $query->whereName($course);
            });
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['filter[completions]' => $course]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_sorted_by_employee_name()
    {
        $employees = $this->manager->employees->sortBy('name')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['sort' => 'name']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $employees,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function employees_can_be_sorted_by_employee_username()
    {
        $employees = $this->manager->employees->sortBy('username')->pluck('username');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['sort' => 'username']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $employees,
            collect(json_decode($response->content(), true)['data'])->pluck('username')
        );
    }

    /** @test */
    public function employees_can_be_sorted_by_employee_completed_courses_count()
    {
        $employees = $this->manager->employees->sortBy('completions_count')->pluck('completions_count');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'employees.index',
                    ['sort' => 'completions_count']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $employees,
            collect(json_decode($response->content(), true)['data'])->pluck('completions_count')
        );
    }
}
