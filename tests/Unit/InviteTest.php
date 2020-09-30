<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Invite;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class InviteTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company()
    {
        $invite = Invite::factory()->for(
            Company::factory()
        )->create();

        $this->assertEquals(Company::first(), $invite->company);
        $this->assertTrue($invite->company->is(Company::first()));
    }

    /** @test */
    public function it_belongs_to_a_sender()
    {
        $invite = Invite::factory()->for(
            Manager::factory(),
            'sender'
        )->create();

        $this->assertEquals(Manager::first(), $invite->sender);
        $this->assertTrue($invite->sender->is(Manager::first()));
    }

    /** @test */
    public function it_can_be_revoked()
    {
        $invite = Invite::factory()->create();

        $this->assertTrue($invite->isNotRevoked());

        $invite->revoke();

        $this->assertTrue($invite->isRevoked());
    }

    /** @test */
    public function it_knows_if_it_is_expired()
    {
        $invite = Invite::factory()->create();

        $this->assertTrue($invite->isNotExpired());

        $this->travel(1)->days();
        $this->travel(1)->seconds();

        $this->assertTrue($invite->isExpired());
    }
}
