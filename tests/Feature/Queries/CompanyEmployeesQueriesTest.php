<?php

namespace Tests\Feature\Queries;

use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class CompanyEmployeesQueriesTest extends TestCase
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
        $name = $this->manager->company->employees->first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[name]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function employees_can_be_filtered_by_username()
    {
        $username = $this->manager->company->employees->first()->username;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[username]' => $username]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['username' => $username]);
    }

    /** @test */
    public function employees_can_be_filtered_by_email()
    {
        $email = $this->manager->company->employees->first()->email;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[email]' => $email]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['email' => $email]);
    }

    /** @test */
    public function employees_can_be_filtered_by_sex()
    {
        $sex = $this->manager->company->employees->first()->sex;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[sex]' => $sex]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->company->employees->where('sex', $sex)->count(), 'data')
            ->assertJsonFragment(['sex' => $sex]);
    }

    /** @test */
    public function employees_can_be_filtered_by_position()
    {
        $position = $this->manager->company->employees->first()->position;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[position]' => $position]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->company->employees->where('position', $position)->count(), 'data')
            ->assertJsonFragment(['position' => $position]);
    }

    /** @test */
    public function employees_can_be_filtered_by_used_technologies_name()
    {
        $technology = $this->manager->company->employees->first()->technologies->first()->name;
        $employees = $this->manager->company->employees()->whereHas('technologies', function (Builder $query) use ($technology) {
            return $query->whereName($technology);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[technologies]' => $technology]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_used_technologies_slug()
    {
        $technology = $this->manager->company->employees->first()->technologies->first()->slug;
        $employees = $this->manager->company->employees()->whereHas('technologies', function (Builder $query) use ($technology) {
            return $query->whereSlug($technology);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[technologies]' => $technology]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_development_directions_name()
    {
        $direction = $this->manager->company->employees->first()->directions->first()->name;
        $employees = $this->manager->company->employees()->whereHas('directions', function (Builder $query) use ($direction) {
            return $query->whereName($direction);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[directions]' => $direction]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_development_directions_slug()
    {
        $direction = $this->manager->company->employees->first()->directions->first()->slug;
        $employees = $this->manager->company->employees()->whereHas('directions', function (Builder $query) use ($direction) {
            return $query->whereSlug($direction);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[directions]' => $direction]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_teams_name()
    {
        $team = $this->manager->company->employees->first()->teams->first()->name;
        $employees = $this->manager->company->employees()->whereHas('teams', function (Builder $query) use ($team) {
            return $query->whereName($team);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[teams]' => $team]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_teams_slug()
    {
        $team = $this->manager->company->employees->first()->teams->first()->slug;
        $employees = $this->manager->company->employees()->whereHas('teams', function (Builder $query) use ($team) {
            return $query->whereSlug($team);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[teams]' => $team]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_assigned_presets_name()
    {
        $preset = $this->manager->company->employees->first()->presets->first()->name;
        $employees = $this->manager->company->employees()->whereHas('presets', function (Builder $query) use ($preset) {
            return $query->whereName($preset);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[presets]' => $preset]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_assigned_presets_slug()
    {
        $preset = $this->manager->company->employees->first()->presets->first()->slug;
        $employees = $this->manager->company->employees()->whereHas('presets', function (Builder $query) use ($preset) {
            return $query->whereSlug($preset);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[presets]' => $preset]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_assigned_courses_name()
    {
        $course = $this->manager->company->employees->first()->courses->first()->name;
        $employees = $this->manager->company->employees()->whereHas('courses', function (Builder $query) use ($course) {
            return $query->where('courses.name', $course);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[courses]' => $course]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_assigned_courses_slug()
    {
        $course = $this->manager->company->employees->first()->courses->first()->slug;
        $employees = $this->manager->company->employees()->whereHas('courses', function (Builder $query) use ($course) {
            return $query->where('courses.slug', $course);
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[courses]' => $course]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_completed_courses_name()
    {
        $course = $this->manager->company->employees->first()->completions->first()->course->name;
        $employees = $this->manager->company->employees()->whereHas('completions', function (Builder $query) use ($course) {
            return $query->whereHas('course', function (Builder $query) use ($course) {
                return $query->whereName($course);
            });
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[completed_courses]' => $course]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_completed_courses_slug()
    {
        $course = $this->manager->company->employees->first()->completions->first()->course->slug;
        $employees = $this->manager->company->employees()->whereHas('completions', function (Builder $query) use ($course) {
            return $query->whereHas('course', function (Builder $query) use ($course) {
                return $query->whereSlug($course);
            });
        })->get();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[completed_courses]' => $course]
                )
            )
            ->assertOk()
            ->assertJsonCount($employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_manager_name()
    {
        $manager = $this->manager->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[manager]' => $manager]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_manager_username()
    {
        $manager = $this->manager->username;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[manager]' => $manager]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_filtered_by_their_manager_email()
    {
        $manager = $this->manager->email;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'filter[manager]' => $manager]
                )
            )
            ->assertOk()
            ->assertJsonCount($this->manager->employees->count(), 'data');
    }

    /** @test */
    public function employees_can_be_sorted_by_employee_name()
    {
        $employees = $this->manager->company->employees->sortBy('name')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'sort' => 'name']
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
        $employees = $this->manager->company->employees->sortBy('username')->pluck('username');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'sort' => 'username']
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
        $employees = $this->manager->company->employees->sortBy('completions_count')->pluck('completions_count');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'sort' => 'completions_count']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $employees,
            collect(json_decode($response->content(), true)['data'])->pluck('completions_count')
        );
    }

    /** @test */
    public function a_user_can_specify_what_attributes_should_be_returned()
    {
        $attributes = "name,username";

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'companies.show',
                    [
                        'company' => $this->manager->company,
                        'show[user]' => $attributes]
                )
            )
            ->assertOk();

        $this->assertEquals(
            json_decode($response->content(), true)['data'],
            $this->manager->company->employees->map->only(explode(',', $attributes))->toArray()
        );
    }
}
