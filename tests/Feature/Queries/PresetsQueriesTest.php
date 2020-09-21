<?php

namespace Tests\Feature\Queries;

use App\Models\Course;
use App\Models\Preset;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PresetsQueriesTest extends TestCase
{
    public $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->manager = Manager::first();
    }

    /** @test */
    public function presets_can_be_filtered_by_name()
    {
        $name = $this->manager->presets->first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['filter[name]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function presets_can_be_filtered_by_creator_name()
    {
        $creator = $this->manager->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['filter[creator]' => $creator]
                )
            )
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $creator]);
    }

    /** @test */
    public function presets_can_be_filtered_by_course_name()
    {
        $name = Course::first()->name;

        $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['filter[courses]' => $name]
                )
            )
            ->assertOk()
            ->assertJsonCount(Preset::whereHas('courses', function (Builder $query) use ($name) {
                return $query->whereName($name);
            })->count(), 'data')
            ->assertJsonFragment(['name' => $name]);
    }

    /** @test */
    public function presets_can_be_sorted_by_name()
    {
        $presets = Preset::get()->sortBy('name')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['sort' => 'name']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $presets,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function presets_can_be_sorted_by_courses_count()
    {
        $presets = Preset::withCount('courses')->get()->sortBy('courses_count')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['sort' => 'courses_count']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $presets,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function presets_can_be_sorted_by_manager_id()
    {
        $presets = Preset::get()->sortBy('manager_id')->pluck('name');

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['sort' => 'manager']
                )
            )
            ->assertOk();

        $this->assertEquals(
            $presets,
            collect(json_decode($response->content(), true)['data'])->pluck('name')
        );
    }

    /** @test */
    public function a_user_can_specify_what_attributes_should_be_returned()
    {
        $attributes = "name,description";

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'presets.index',
                    ['show[preset]' => $attributes]
                )
            )
            ->assertOk();

        $this->assertEquals(
            json_decode($response->content(), true)['data'],
            Preset::all()->map->only(explode(',', $attributes))->toArray()
        );
    }
}
