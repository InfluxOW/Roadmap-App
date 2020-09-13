<?php

namespace Tests\Feature;

use App\Models\Preset;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class PresetsControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;
    public $presets;
    public $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = Employee::factory()->create();
        $this->manager = Manager::factory()->create();
        $this->admin = Admin::factory()->create();

        $this->attributes = Preset::factory()->raw();
        $this->presets = Preset::factory()->count(3)->create();
    }

    /** @test */
    public function a_guest_cannot_perform_any_actions()
    {
        $this->get(route('presets.index'))
            ->assertRedirect(route('login'));

        $this->get(route('presets.show', $this->presets->first()))
            ->assertRedirect(route('login'));

        $this->post(route('presets.store'), $this->attributes)
            ->assertRedirect(route('login'));

        $this->patch(route('presets.update', $this->presets->first()), $this->attributes)
            ->assertRedirect(route('login'));

        $this->delete(route('presets.update', $this->presets->first()), $this->attributes)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_employee_cannot_perform_any_actions()
    {
        $this->actingAs($this->employee, 'api')
            ->get(route('presets.index'))
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->get(route('presets.show', $this->presets->first()))
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->post(route('presets.store'), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->patch(route('presets.update', $this->presets->first()), $this->attributes)
            ->assertForbidden();

        $this->actingAs($this->employee, 'api')
            ->delete(route('presets.update', $this->presets->first()), $this->attributes)
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_view_presets()
    {
        $this->actingAs($this->admin, 'api')
            ->get(route('presets.index'))
            ->assertOk()
            ->assertJsonCount($this->presets->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'description', 'courses', 'assigned_to']
                ]
            ]);
    }


    /** @test */
    public function a_manager_can_view_presets()
    {
        $this->actingAs($this->manager, 'api')
            ->get(route('presets.index'))
            ->assertOk()
            ->assertJsonCount($this->presets->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'description', 'courses', 'assigned_to']
                ]
            ]);
    }

    /** @test */
    public function an_admin_can_view_a_specific_preset()
    {
        $this->actingAs($this->admin, 'api')
            ->get(route('presets.show', $this->presets->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'courses', 'assigned_to']]);
    }

    /** @test */
    public function a_manager_can_view_a_specific_preset()
    {
        $this->actingAs($this->manager, 'api')
            ->get(route('presets.show', $this->presets->first()))
            ->assertOk()
            ->assertJsonStructure(['data' => ['name', 'description', 'courses', 'assigned_to']]);
    }

    /** @test */
    public function an_admin_can_create_a_new_preset()
    {
        $this->actingAs($this->admin, 'api')
            ->post(route('presets.store'), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseCount('presets', $this->presets->count() + 1);
        $this->assertDatabaseHas('presets', $this->attributes);
    }

    /** @test */
    public function a_manager_can_create_a_new_preset()
    {
        $this->actingAs($this->manager, 'api')
            ->post(route('presets.store'), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseCount('presets', $this->presets->count() + 1);
        $this->assertDatabaseHas('presets', $this->attributes);
    }

    /** @test */
    public function an_admin_can_update_a_specific_preset()
    {
        $this->actingAs($this->admin, 'api')
            ->patch(route('presets.update', $this->presets->first()), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseHas('presets', $this->attributes);
    }

    /** @test */
    public function a_preset_owner_can_update_it()
    {
        $preset = Preset::factory()->create(['manager_id' => $this->manager]);

        $this->actingAs($this->manager, 'api')
            ->patch(route('presets.update', $preset), $this->attributes)
            ->assertRedirect();

        $this->assertDatabaseHas('presets', $this->attributes);
    }

    /** @test */
    public function a_manager_cannot_update_a_preset_that_doesnt_belongs_to_him()
    {
        $this->actingAs($this->manager, 'api')
            ->patch(route('presets.update', $this->presets->first()), $this->attributes)
            ->assertForbidden();

        $this->assertDatabaseMissing('presets', $this->attributes);
    }


    /** @test */
    public function an_admin_can_delete_a_specific_preset()
    {
        $preset = $this->presets->first();
        $this->actingAs($this->admin, 'api')
            ->delete(route('presets.destroy', $preset))
            ->assertRedirect();

        $this->assertDatabaseMissing('presets', ['id' => $preset->id]);
    }

    /** @test */
    public function a_preset_owner_can_delete_it()
    {
        $preset = Preset::factory()->create(['manager_id' => $this->manager]);

        $this->actingAs($this->manager, 'api')
            ->delete(route('presets.destroy', $preset))
            ->assertRedirect();

        $this->assertDatabaseMissing('presets', ['id' => $preset->id]);
    }

    /** @test */
    public function a_manager_cannot_delete_a_preset_that_doesnt_belongs_to_him()
    {
        $preset = $this->presets->first();
        $this->actingAs($this->manager, 'api')
            ->delete(route('presets.update', $preset))
            ->assertForbidden();

        $this->assertDatabaseHas('presets', ['id' => $preset->id]);
    }
}
