<?php

namespace Tests\Feature\Queries;

use App\Models\Course;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoadmapsQueriesTest extends TestCase
{
    public $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->manager = Manager::first();
    }

    /** @test */
    public function a_user_can_specify_what_attributes_should_be_returned()
    {
        $attributes = "preset,assigned_by";

        $response = $this->actingAs($this->manager)
            ->get(
                route(
                    'roadmaps.index',
                    ['show[roadmap]' => $attributes]
                )
            )
            ->assertOk();

        $this->assertEquals(
            array_keys(json_decode($response->content(), true)['data'][0]),
            array_keys($this->manager->roadmaps->map->only(explode(',', $attributes))->toArray()[0])
        );
    }
}
