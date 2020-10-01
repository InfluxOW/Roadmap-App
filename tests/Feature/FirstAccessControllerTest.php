<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FirstAccessControllerTest extends TestCase
{
    public $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        Admin::factory()->create();
        $this->attributes = ['company' => Company::factory()->raw(), 'email' => 'test@gmail.com'];
    }

    /** @test
     *  @doesNotPerformAssertions
     */
    public function a_user_can_ask_for_a_first_access()
    {
//        $res = $this->post(route('first_access'), $this->attributes);
    }
}
