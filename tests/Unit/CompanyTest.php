<?php

namespace Tests\Unit;

use App\Models\Company;
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
    public function it_may_have_teams()
    {
        $company = Company::factory()->has(
            Team::factory()->count($count = 3)
        )->create();

        $this->assertTrue($company->teams->contains(Team::first()));
        $this->assertInstanceOf(Team::class, $company->teams->first());
        $this->assertCount($count, $company->teams);
    }
}
