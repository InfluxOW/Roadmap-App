<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Invite;
use App\Models\Team;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /** @test */
    public function it_may_have_managers()
    {
        $company = Company::factory()->has(
            Manager::factory()->count($count = 3)
        )->create();

        $this->assertTrue($company->managers->contains(Manager::first()));
        $this->assertInstanceOf(Manager::class, $company->managers->first());
        $this->assertCount($count, $company->managers);
    }

    /** @test */
    public function it_may_have_employees()
    {
        $company = Company::factory()->has(
            Employee::factory()->count($count = 3)
        )->create();

        $this->assertTrue($company->employees->contains(Employee::first()));
        $this->assertInstanceOf(Employee::class, $company->employees->first());
        $this->assertCount($count, $company->employees);
    }

    /** @test */
    public function it_may_have_invites()
    {
        $company = Company::factory()->has(
            Invite::factory()->count($count = 3)
        )->create();

        $this->assertTrue($company->invites->contains(Invite::first()));
        $this->assertInstanceOf(Invite::class, $company->invites->first());
        $this->assertCount($count, $company->invites);
    }
}
