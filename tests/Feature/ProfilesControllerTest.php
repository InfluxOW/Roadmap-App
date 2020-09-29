<?php

namespace Tests\Feature;

use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfilesControllerTest extends TestCase
{
    public $employee;
    public $manager;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->admin = Admin::factory()->create();
    }

    /** @test */
    public function a_user_redirects_to_his_profile_trying_to_view_profiles()
    {
        $this->actingAs($this->employee)
            ->get(route('profiles.index'))
            ->assertRedirect(route('profiles.show', $this->employee));

        $this->actingAs($this->manager)
            ->get(route('profiles.index'))
            ->assertRedirect(route('profiles.show', $this->manager));

        $this->actingAs($this->admin)
            ->get(route('profiles.index'))
            ->assertRedirect(route('profiles.show', $this->admin));
    }

    /** @test */
    public function only_admin_can_view_other_users_profiles()
    {
        $this->actingAs($this->employee)
            ->get(route('profiles.show', $this->manager))
            ->assertForbidden();

        $this->actingAs($this->manager)
            ->get(route('profiles.show', $this->employee))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->get(route('profiles.show', $this->employee))
            ->assertOk();
    }
}
