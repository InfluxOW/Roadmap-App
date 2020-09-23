<?php

namespace Tests\Feature;

use App\Models\Preset;
use App\Models\Technology;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class PresetGenerationsControllerTest extends TestCase
{
    public $manager;
    public $employee;
    public $admin;
    public $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->manager = Manager::first();
        $this->employee = Employee::first();
        $this->admin = Admin::factory()->create();
        $this->attributes = [
            'name' => 'Test Preset',
            'description' => 'Test Description',
            'technologies' => Technology::take(5)->pluck('name')->toArray(),
        ];
    }

    /** @test */
    public function an_employee_cannot_generate_a_preset()
    {
        $this->actingAs($this->employee)
            ->post(route('presets.generate'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_generate_a_preset()
    {
        $response = $this->actingAs($this->manager)
            ->post(route('presets.generate'), $this->attributes)
            ->assertCreated();
        $preset = Preset::whereName(json_decode($response->content(), true)['data']['name'])->first();

        $this->assertDatabaseHas('presets', Arr::only($this->attributes, ['name', 'description']));
        $this->assertNotEmpty($preset->courses);
    }

    /** @test */
    public function an_admin_can_generate_a_preset()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('presets.generate'), $this->attributes)
            ->assertCreated();
        $preset = Preset::whereName(json_decode($response->content(), true)['data']['name'])->first();

        $this->assertDatabaseHas('presets', Arr::only($this->attributes, ['name', 'description']));
        $this->assertNotEmpty($preset->courses);
    }
}
