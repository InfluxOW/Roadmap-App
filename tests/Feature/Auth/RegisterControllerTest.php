<?php

namespace Tests\Feature\Auth;

use App\Models\Invite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    /** @test */
    public function a_new_employee_can_be_created_via_invite()
    {
        $invite = Invite::factory()->create(['role' => 'employee']);
        $attributes = ['name' => 'Test', 'username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password'];

        $this->post(
            route('register', ['invite_token' => $invite->code]),
            $attributes
        )
        ->assertOk();

        $data = array_merge(Arr::only($attributes, ['name', 'username']), $invite->only('role'));
        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function a_new_manager_can_be_created_via_invite()
    {
        $invite = Invite::factory()->create(['role' => 'manager']);
        $attributes = ['name' => 'Test', 'username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password'];

        $this->post(
            route('register', ['invite_token' => $invite->code]),
            $attributes
        )
            ->assertOk();

        $data = array_merge(Arr::only($attributes, ['name', 'username']), $invite->only('role'));
        $this->assertDatabaseHas('users', $data);
    }
}
