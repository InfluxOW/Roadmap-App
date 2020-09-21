<?php

namespace Tests\Feature\Auth;

use App\Models\UserTypes\Employee;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = Employee::factory()->create();
    }

    /** @test */
    public function a_user_cannot_login_with_wrong_credentials()
    {
        $this->post(route('login'), ['email' => $this->employee->email, 'password' => 'wrong_password'])
            ->assertUnauthorized();
    }

    /** @test */
    public function a_user_can_login_with_correct_credentials()
    {
        $this->post(route('login'), ['email' => $this->employee->email, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['user', 'access_token']);

        $this->assertAuthenticatedAs($this->employee);
    }
}
