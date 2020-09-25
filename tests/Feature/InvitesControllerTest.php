<?php

namespace Tests\Feature;

use App\Events\InviteCreated;
use App\Jobs\SendMail;
use App\Listeners\SendInvite;
use App\Models\Company;
use App\Models\Invite;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InvitesControllerTest extends TestCase
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
    public function an_employee_cannot_send_invites()
    {
        $this->actingAs($this->employee)
            ->post(route('invites.store'))
            ->assertForbidden();
    }

    /** @test */
    public function a_manager_can_send_invites()
    {
        Event::fake(InviteCreated::class);
        $attributes = ['email' => 'test@mail.com'];

        $this->actingAs($this->manager)
            ->post(route('invites.store'), $attributes)
            ->assertOk();

        $this->assertDatabaseHas('invites', $attributes);
    }

    /** @test */
    public function a_manager_can_invite_only_employees()
    {
        Event::fake(InviteCreated::class);
        $attributes = ['email' => 'test@mail.com', 'role' => 'manager'];

        $this->actingAs($this->manager)
            ->post(route('invites.store'), $attributes)
            ->assertOk();

        $this->assertDatabaseMissing('invites', $attributes);

        $attributes['role'] = 'employee';
        $this->assertDatabaseHas('invites', $attributes);
    }

    /** @test */
    public function an_admin_can_send_invites()
    {
        Event::fake(InviteCreated::class);
        $attributes = ['email' => 'test@mail.com', 'role' => 'manager', 'company' => Company::first()->slug];

        $this->actingAs($this->admin)
            ->post(route('invites.store'), $attributes)
            ->assertOk();

        $this->assertDatabaseHas('invites', Arr::only($attributes, ['email, role']));
    }

    /** @test */
    public function sending_an_invite_dispatches_invite_created_event()
    {
        Event::fake(InviteCreated::class);
        $attributes = ['email' => 'test@mail.com'];

        $this->actingAs($this->manager)
            ->post(route('invites.store'), $attributes)
            ->assertOk();

        Event::assertDispatched(InviteCreated::class);
    }

    /** @test */
    public function send_invite_listener_sends_user_invite_email_to_the_invited_user_email()
    {
        $event = $this->mockInviteCreatedEvent();

        Queue::fake();

        $listener = app()->make(SendInvite::class);
        $listener->handle($event);

        Queue::assertPushed(SendMail::class);
    }

    private function mockInviteCreatedEvent()
    {
        $attributes = ['email' => 'test@mail.com'];
        $this->actingAs($this->manager)
            ->post(route('invites.store'), $attributes);

        $event = \Mockery::mock(InviteCreated::class);
        $event->invite = Invite::first();

        return $event;
    }
}
