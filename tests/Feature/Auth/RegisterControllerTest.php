<?php

namespace Tests\Feature\Auth;

use App\Models\Invite;
use App\Models\Team;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Builder;
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

    /** @test */
    public function a_newly_registered_manager_has_default_team()
    {
        $invite = Invite::factory()->create(['role' => 'manager']);
        $attributes = ['name' => 'Test', 'username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password'];

        $this->post(route('register', ['invite_token' => $invite->code]), $attributes);
        $manager = Manager::whereName($attributes['name'])->whereUsername($attributes['username'])->first();

        $this->assertTrue($manager->teams()->where(function (Builder $query) {
            return $query->whereName('Default Team');
        })->exists());
        $this->assertDatabaseHas('teams', ['owner_id' => $manager->id, 'name' => 'Default Team']);
    }

    /** @test */
    public function newly_registered_employee_invited_by_manager_assigns_to_his_default_team()
    {
        $manager = Manager::factory()->has(Team::factory(['name' => 'Default Team']))->create();
        $invite = Invite::factory()->create(['role' => 'employee', 'sent_by_id' => $manager->id, 'company_id' => $manager->company->id]);
        $attributes = ['name' => 'Employee', 'username' => 'employee', 'password' => 'password', 'password_confirmation' => 'password'];
        $this->post(route('register', ['invite_token' => $invite->code]), $attributes);

        $employee = Employee::whereName($attributes['name'])->whereUsername($attributes['username'])->first();

        $this->assertTrue($employee->teams()->where(function (Builder $query) use ($manager) {
            return $query->whereName('Default Team')->where('owner_id', $manager->id);
        })->exists());
    }
}
