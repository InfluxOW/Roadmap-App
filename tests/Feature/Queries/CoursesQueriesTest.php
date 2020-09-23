<?php

namespace Tests\Feature\Queries;

use App\Models\Course;
use App\Models\Preset;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CoursesQueriesTest extends TestCase
{
    public $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->manager = Manager::first();
    }

    /** @test */
    public function courses_can_be_filtered_by_name()
    {
        $name = Course::first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[name]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function courses_can_be_filtered_by_source()
    {
        $source = Course::first()->source;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[source]' => $source]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['source' => $source]);
    }

    /** @test */
    public function courses_can_be_filtered_by_levels_name()
    {
        $level = Course::first()->level->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[levels]' => $level]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::whereHas('level', function (Builder $query) use ($level) {
                return $query->whereName($level);
            })->count(), 'data')
            ->assertJsonFragment(['level' => $level]);
    }

    /** @test */
    public function courses_can_be_filtered_by_levels_slug()
    {
        $level = Course::first()->level->slug;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[levels]' => $level]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::whereHas('level', function (Builder $query) use ($level) {
                return $query->whereSlug($level);
            })->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_technologies_name()
    {
        $technology = Course::first()->technologies->first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[technologies]' => $technology]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::whereHas('technologies', function (Builder $query) use ($technology) {
                return $query->whereName($technology);
            })->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_technologies_slug()
    {
        $technology = Course::first()->technologies->first()->slug;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[technologies]' => $technology]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::whereHas('technologies', function (Builder $query) use ($technology) {
                return $query->whereSlug($technology);
            })->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_presets_name()
    {
        $preset = Preset::first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[presets]' => $preset]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::whereHas('presets', function (Builder $query) use ($preset) {
                return $query->whereName($preset);
            })->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_presets_slug()
    {
        $preset = Preset::first()->slug;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[presets]' => $preset]
                )
            )
            ->assertOk()
            ->assertJsonCount(Course::whereHas('presets', function (Builder $query) use ($preset) {
                return $query->whereSlug($preset);
            })->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_completed_by_the_specific_employee_name()
    {
        $employee = $this->manager->employees->first();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[completed_by]' => $employee->name]
                )
            )
            ->assertOk()
            ->assertJsonCount($employee->completions->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_completed_by_the_specific_employee_username()
    {
        $employee = $this->manager->employees->first();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[completed_by]' => $employee->username]
                )
            )
            ->assertOk()
            ->assertJsonCount($employee->completions->count(), 'data');
    }

    /** @test */
    public function courses_can_be_filtered_by_completed_by_the_specific_employee_email()
    {
        $employee = $this->manager->employees->first();

        $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['filter[completed_by]' => $employee->email]
                )
            )
            ->assertOk()
            ->assertJsonCount($employee->completions->count(), 'data');
    }

    /** @test */
    public function courses_can_be_sorted_by_name()
    {
        $courses = Course::orderBy('name')->take(20)->get()->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['sort' => 'name']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $courses,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function courses_can_be_sorted_by_source()
    {
        $courses = Course::orderBy('source')->take(20)->get()->pluck('source');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['sort' => 'source']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $courses,
            collect(json_decode($response->content(), true)['data'])->pluck('source')
        );
    }

    /** @test */
    public function courses_can_be_sorted_by_level()
    {
        $courses = Course::orderBy('employee_level_id')->take(20)->get()->pluck('level.name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['sort' => 'level']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $courses,
            collect(json_decode($response->content(), true)['data'])->pluck('level')
        );
    }

    /** @test */
    public function a_user_can_specify_what_attributes_should_be_returned()
    {
        $attributes = "name,source";

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'courses.index',
                    ['show[course]' => $attributes]
                )
            )
            ->assertOk();

        $this->assertEquals(
            json_decode($response->content(), true)['data'],
            Course::take(20)->get()->map->only(explode(',', $attributes))->toArray()
        );
    }
}
