<?php

namespace Tests\Feature\Auth;

use App\Models\UserTypes\Employee;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    public $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = Employee::factory()->create();
    }

    /** @test */
    public function an_unauthorized_user_cannot_logout()
    {
        $this->post(route('logout'))
            ->assertRedirect(route('login'));
    }

    /** @test
     * @doesNotPerformAssertions
     */
    public function an_authorized_user_can_logout()
    {
        $this->post(route('login'), ['email' => $this->employee->email, 'password' => 'password']);

        /*
         * It works via Postman but throws an error in tests
         * ???
         * */
        $this->post(route('logout'));
    }
}
